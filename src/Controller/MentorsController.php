<?php
// /src/Controller/MentorsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\Mentor;
use App\Models\MentorRequest;
use App\Models\UserType;
use App\Models\Country;
use Src\Controller\NotificationsController;
use App\Utils\IdEncoder;
use App\Traits\RecentActivityLogger;
use Illuminate\Database\Capsule\Manager as Capsule;

class MentorsController
{
    use RecentActivityLogger;

    /**
     * Handle Delete for a Mentor Profile
     */
    public function delete($id): array
    {
        try {
            $userId = (int)($_SESSION['user_id'] ?? 0);
            if ($userId === 0) throw new \Exception("Unauthorized.");

            $rawId = (is_string($id) && !is_numeric($id)) ? IdEncoder::decode($id) : (int)$id;
            $mentor = Mentor::find($rawId);

            if ($mentor) {
                if ((int)$mentor->orig_user_id !== $userId) {
                    throw new \Exception("You do not have permission to delete this profile.");
                }

                $headline = $mentor->headline;

                if ($mentor->delete()) {
                    static::logActivity("Deleted mentor specialty: {$headline}", 'Mentors');

                    return [
                        'success' => true,
                        'messages' => ["Expertise '{$headline}' removed successfully."]
                    ];
                }
            }

            return ['success' => false, 'messages' => ['Failed to locate mentor profile.']];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Prepare data for the Expert Network / Mentors Page
     */
    public function index(): void
    {
        $query = $_GET['q'] ?? '';
        $targetType = (int)($_GET['target_type'] ?? 0);
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $builder = Mentor::with(['user', 'targetUserType', 'country', 'region'])
            ->where('is_active', true);

        if (!empty($query)) {
            $builder->where(function ($q) use ($query) {
                $term = '%' . trim($query) . '%';
                $q->where('headline', 'LIKE', $term)
                    ->orWhere('bio', 'LIKE', $term)
                    ->orWhere('skills', 'LIKE', $term)
                    ->orWhere('city', 'LIKE', $term)
                    ->orWhereHas('country', function ($sq) use ($term) {
                        $sq->where('country', 'LIKE', $term);
                    })
                    ->orWhereHas('region', function ($sq) use ($term) {
                        $sq->where('region', 'LIKE', $term);
                    });
            });
        }

        if ($targetType > 0) {
            $builder->where('target_user_type_id', $targetType);
        }

        $totalFiltered = $builder->count();
        $mentors = $builder->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $html = '';
        foreach ($mentors as $mentor) {
            $html .= self::renderCard($mentor);
        }

        if (isset($_GET['page']) || isset($_GET['q']) || isset($_GET['target_type'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'html'    => $html,
                'total'   => $totalFiltered,
                'hasMore' => ($offset + $mentors->count()) < $totalFiltered
            ]);
            exit;
        }

        $GLOBALS['mentorCards'] = $html;
        $GLOBALS['userTypes']   = UserType::orderBy('user_type', 'asc')->get()->toArray();
        $GLOBALS['countries']   = Country::orderBy('country', 'asc')->get()->toArray();
        $GLOBALS['totalMentors'] = $totalFiltered;
        $GLOBALS['title']        = "Expert Network";
    }

    /**
     * Handle Create or Update for a Mentor Profile
     */
    public function save(array $data): array
    {
        try {
            $encodedId = $data['encoded_id'] ?? null;
            $isNew = empty($encodedId);
            $userId = (int)($_SESSION['user_id'] ?? 0);

            if ($userId === 0) throw new \Exception("Unauthorized.");

            $id = !$isNew ? IdEncoder::decode($encodedId) : null;
            $mentor = $id ? Mentor::find($id) : new Mentor();

            $mentor->orig_user_id        = $userId;
            $mentor->target_user_type_id = (int)($data['target_user_type_id'] ?? 0);
            $mentor->country_id          = (int)($data['country_id'] ?? 1);
            $mentor->region_id           = (int)($data['region_id'] ?? 0);
            $mentor->city                = trim($data['city'] ?? '');
            $mentor->headline            = trim($data['headline'] ?? '');
            $mentor->bio                 = trim($data['bio'] ?? '');
            $mentor->years_experience    = (int)($data['years_experience'] ?? 0);
            $mentor->youtube_url         = trim($data['youtube_url'] ?? '');
            $mentor->website_url         = trim($data['website_url'] ?? '');
            $mentor->is_active           = isset($data['is_active']) ? (bool)$data['is_active'] : true;

            if (isset($data['skills'])) {
                $skillsArray = is_array($data['skills']) ? $data['skills'] : explode(',', $data['skills']);
                $mentor->skills = array_map('trim', $skillsArray);
            }

            $mentor->save();

            $actionLabel = $isNew ? "Registered as a mentor" : "Updated mentor profile";
            static::logActivity("{$actionLabel}: {$mentor->headline}", 'Mentors');

            return [
                'success'  => true,
                'cardHtml' => self::renderCard($mentor->fresh(['user', 'targetUserType', 'country', 'region'])),
                'messages' => ['Mentor profile saved successfully.']
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'messages' => [$e->getMessage()]];
        }
    }

    /**
     * Render individual Mentor Card
     */
    public static function renderCard(Mentor $mentor): string
    {
        $item = $mentor->toArray();
        $item['encoded_id'] = IdEncoder::encode((int)$mentor->id);
        $GLOBALS['assetBase'] = getAssetBase();

        // 1. Handle Owner (The User profile)
        $owner = $mentor->user;
        $item['owner'] = $owner ? $owner->toArray() : null;

        // 2. Map Mentor Location (Specific to this Mentor Post) 💎
        $item['country_name'] = $mentor->country->country ?? 'N/A';
        $item['region_name']  = $mentor->region->region ?? 'N/A';

        // 3. Map Owner Geography (The User's home location)
        $item['owner_country'] = $owner->country->country ?? 'N/A';
        $item['owner_region']  = $owner->region->region ?? 'N/A';

        // 4. Roles Mapping
        $item['user_types_json'] = getUserRoles($owner);

        // 5. Target Audience Mapping
        $item['target_user_type'] = $mentor->targetUserType ? $mentor->targetUserType->toArray() : null;

        $path = __DIR__ . '/../../resources/views/components/mentors/data-card.php';

        ob_start();
        try {
            // Passing variables explicitly to prevent Scope issues
            $assetBase = getAssetBase();
            include $path;
        } catch (\Throwable $e) {
            ob_end_clean();

            return "<div class='p-4 text-red-500 font-bold'>Render Error: " . $e->getMessage() . "</div>";
        }
        return ob_get_clean();
    }
}
