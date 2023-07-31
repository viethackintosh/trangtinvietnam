import { buildTag } from "./modules/helpers/buildtag.js";
const App = function() {
    const app = this;
    
    /**
     * khởi tạo app
     */
    app.init = async () => {
        const pageType = document.querySelector('meta#pagetype').content;
        const pageTypeModule = await import(`./modules/layout/${pageType}.js`);
        const layout = new pageTypeModule.default();
        layout.init({type:pageType });
    }
}
const hack = new App();
hack.init();