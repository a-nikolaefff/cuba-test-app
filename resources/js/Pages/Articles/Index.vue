<template>
    <Head title="Импорт статей"/>

    <form @submit.prevent="importArticle">
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0">
            <CustomTextInput v-model="importForm.title"
                             class="w-full md:w-1/2"
                             :class="{'border-red-700': importFormErrors.title &&!isLoading}"
                             placeholder="Введите название статьи на ru.wikipedia.org"
            />
            <CustomButton type="submit" class="w-full sm:w-[140px] sm:ml-2">
                Скопировать
            </CustomButton>
        </div>
    </form>

    <Alert type="error" v-if="importFormErrors.title &&!isLoading">
        {{ importFormErrors.title }}
    </Alert>

    <Alert type="success" v-if="requestTime && !isLoading && !importFormErrors.title">
        <p>Импорт статьи успешно завершен</p>
        <p>Время обработки составило: {{ requestTime }} секунд</p>
        <p>Импортирована статья по адресу:
            <a class="underline" href="{{ localArticles[0].url }}">
                {{ localArticles[0].url }}
            </a>
        </p>
        <p>Размер статьи: {{ localArticles[0].size_kb }} Кб
        </p>
        <p>Количество слов: {{ localArticles[0].word_count }}</p>
    </Alert>

    <div v-if="isLoading" class="h-[136px] flex justify-center items-center">
        <Loader>
            Импорт статьи...
        </Loader>
    </div>

    <div class="flex flex-col">
        <div class="-mx-2 overflow-x-auto">
            <div class="px-2 inline-block min-w-full">
                <div v-if="localArticles.length > 0"
                     class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="custom-table">
                        <thead>
                        <tr>
                            <th scope="col">Название статьи</th>
                            <th scope="col">Ссылка</th>
                            <th scope="col">Размер статьи</th>
                            <th scope="col">Количество слов</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="article in localArticles" :key="article.id">
                            <td>{{ article.title }}</td>
                            <td><a :href="article.url">{{ article.url }}</a></td>
                            <td>{{ article.size_kb }} Кб</td>
                            <td>{{ article.word_count }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else-if="!isLoading" class="font-bold text-lg">
                    Импортированные статьи не найдены
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {Head} from "@inertiajs/vue3";
import {ref} from 'vue';
import CustomTextInput from '../../Shared/CustomTextInput.vue'
import CustomButton from "../../Shared/CustomButton.vue";
import Loader from "../../Shared/Loader.vue";
import Alert from "../../Shared/Alert.vue";

export default {
    components: {
        Alert, Loader, Head, CustomTextInput, CustomButton
    },
    props: {
        articles: Object,
    },
    setup(props) {
        const importForm = ref({title: ''});
        const importFormErrors = ref({title: ''});
        const localArticles = ref(props.articles);
        const isLoading = ref(false);
        const requestTime = ref(null);

        async function importArticle() {
            if (importForm.value.title === '') {
                return;
            }

            const startTime = new Date().getTime();
            isLoading.value = true;

            try {
                const response = await axios.post(route('articles.import'), importForm.value);
                const { title, url, size_kb, word_count } = response.data.article;

                localArticles.value.unshift({ title, url, size_kb, word_count });

                importForm.value.title = '';
                importFormErrors.value.title = '';
                requestTime.value = (new Date().getTime() - startTime) / 1000;
            } catch (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    importFormErrors.value.title = error.response.data.errors.title.join('');
                }
            } finally {
                isLoading.value = false;
            }
        }

        return {
            localArticles,
            isLoading,
            requestTime,
            importForm,
            importFormErrors,
            importArticle,
        };
    },
}
</script>


