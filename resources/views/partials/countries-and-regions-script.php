<?php
// /resources/views/partials/countries-and-regions-script.php

?>

<script>
    window.COUNTRIES = <?= json_encode($countries->map(fn($c) => ['id' => $c->country_id, 'name' => $c->country])->toArray()) ?>;
    window.REGIONS_BY_COUNTRY = <?= json_encode(array_map(
                                    fn($r) => $r->map(fn($x) => ['id' => $x->region_id, 'name' => $x->region])->toArray(),
                                    $regionsByCountry
                                )) ?>;
</script>