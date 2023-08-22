<template>
    <div class="sm:container mx-3 sm:mx-auto">
        <div class="min-w-full py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2 sm:space-y-4 py-3">
                <nav
                    class="items-center bg-[#FBFBFB] py-4 text-neutral-500 shadow-md rounded-md">

                    <ol class="list-reset ml-5 flex items-center space-x-6">
                        <li>
                            <Link :href="route('articles.index')"
                                  class="text-neutral-500 font-semibold hover:text-sky-600 transition ease-out duration-300">
                                <div class="logo sm:logo2"></div>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('articles.index')"
                                  class="text-neutral-500 font-semibold hover:text-sky-600 transition ease-out duration-300"
                                  :class="{ 'text-sky-600': isActive(route('articles.index')) }">
                                Импорт статей
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('article.show-search-page')"
                                  class="text-neutral-500 font-semibold hover:text-sky-600 transition ease-out duration-300"
                                  :class="{ 'text-sky-600': isActive(route('article.show-search-page')) }">
                                Поиск
                            </Link>
                        </li>
                    </ol>
                </nav>
                <slot/>
            </div>
        </div>
    </div>

</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {
        Link
    },
    methods: {
        isActive(name) {
            let url = new URL(window.location.href);
            url.search = "";
            return url.toString() === name;
        }
    }
}
</script>
