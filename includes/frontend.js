class Marquee extends elementorModules.frontend.handlers.Base {
    
    onInit(...args) {
        super.onInit(...args);
        this.handleDestroy();
        this.initMarquee();
    }

    getDefaultSettings() {
        return {
            selectors: {
                MarqueeElement: ".jws-marquee",
            }
        }
    }

    getDefaultElements() {
        const selectors = this.getSettings("selectors");
        return {
            $marqueeElement: this.$element.find(selectors.MarqueeElement),
        }
    }

    initMarquee() {
        const $marquee = this.elements.$marqueeElement;
        imagesLoaded($marquee, () => {
            $marquee.jwsMarquee();
        });
    }

    onDestroy() {
        this.handleDestroy();
        super.onDestroy();
    }

    handleDestroy() {
        // Cleanup if needed
    }
}

// Register the handler
jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler(
        "marquee_advanced", 
        Marquee
    );
});