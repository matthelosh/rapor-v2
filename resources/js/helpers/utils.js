import { usePage } from "@inertiajs/vue3";

const page = usePage();

export const cssUrl = () =>
    page.props.app_env == "local"
        ? page.props.appUrl + ":5173/resources/css/app.css"
        : "/build/assets/app.css";

export default { cssUrl };
