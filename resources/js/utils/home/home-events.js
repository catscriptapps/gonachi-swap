// /resources/js/utils/home/home-events.js

import { AnimationEngine } from "../animations.js";
import { initHomeCarousel } from "./home-carousel.js";
import { initRegisterNewUser } from "./register-new-user.js";

export function initHomeEvents() {
    AnimationEngine.refresh();
    initHomeCarousel();
    initRegisterNewUser();
}