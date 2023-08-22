<template>
    <Head title="Поиск по статьям"/>
    <form @submit.prevent="search">
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0">
            <CustomTextInput v-model="keyword"
                             class="w-full md:w-1/2"
                             :class="{'border-red-700': keywordError}"
                             placeholder="Введите ключевое слово"
            />
            <CustomButton type="submit" class="w-full sm:w-[140px] sm:ml-2">
                Найти
            </CustomButton>
        </div>
    </form>

    <Alert type="error" v-if="keywordError">
        {{ keywordError }}
    </Alert>

    <Alert type="success" v-if="totalOccurrences && !keywordError">
        Найдено {{ totalOccurrences }} {{ totalOccurrences <= 4 ? 'совпадения' : 'совпадений' }}
    </Alert>

    <div class="flex flex-col">
        <div class="-mx-2 overflow-x-auto">
            <div class="px-2 inline-block min-w-full">
                <div v-if="localArticles && localArticles.length > 0"
                     class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="custom-table custom-table_clicable">
                        <thead>
                        <tr>
                            <th scope="col">Название статьи</th>
                            <th scope="col">Количество вхождений</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="article in localArticles" :key="article.id" @click="loadAndShowArticleText(article)">
                            <td>{{ article.title }}</td>
                            <td>{{ article.occurrences }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <TextDrawer
        ref="drawerComponent"
        :title="displayedArticle.title"
        :text="displayedArticle.text"
        :is-loading="isLoading"
        loader-text="Загружаем текст статьи..."
        :error="displayedArticleError"
    />
</template>

<script>
import {Head} from "@inertiajs/vue3";
import {ref} from "vue";
import Alert from "../../Shared/Alert.vue";
import Loader from "../../Shared/Loader.vue";
import CustomTextInput from "../../Shared/CustomTextInput.vue";
import CustomButton from "../../Shared/CustomButton.vue";
import TextDrawer from "../../Shared/TextDrawer.vue";

export default {
    components: {
        TextDrawer, Alert, Loader, Head, CustomTextInput, CustomButton
    },
    setup() {
        const drawerComponent = ref(null);
        const keyword = ref('');
        const keywordError = ref('');
        const localArticles = ref(null);
        const totalOccurrences = ref(null);
        const displayedArticle = ref({title: '', text: ''});
        const displayedArticleError = ref('');
        const isLoading = ref(false);

        async function search() {
            if (keyword.value === '') {
                return;
            }

            keywordError.value = '';

            try {
                const response = await axios.get(route('articles.search'), {params: {word: keyword.value}});
                localArticles.value = response.data.articles;
                totalOccurrences.value = response.data.totalOccurrences;
            } catch (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    console.log(error)
                    localArticles.value = null;
                    keywordError.value = error.response.data.errors.word.join('');
                }
            }
        }

        async function loadAndShowArticleText(article) {
            isLoading.value = true;
            displayedArticle.value.title = article.title;
            drawerComponent.value.show();

            try {
                const response = await axios.get(route('articles.get', article.id));
                displayedArticle.value.text = response.data.text;
            } catch (error) {
                displayedArticle.value.text = '';
                if (error.response && error.response.status && error.response.status === 404) {
                    displayedArticleError.value = 'Ошибка 404. Статья не найдена.';
                } else {
                    displayedArticleError.value = 'Произошла неизвестная ошибка. Пожалуйста, обратитесь к администратору или попробуйте позже.';
                }
            } finally {
                isLoading.value = false;
            }
        }

        return {
            drawerComponent,
            localArticles,
            totalOccurrences,
            keyword,
            keywordError,
            displayedArticle,
            displayedArticleError,
            isLoading,
            search,
            loadAndShowArticleText,
        };
    },
}

</script>


