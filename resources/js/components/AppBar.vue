<template>
    <v-app-bar
        app
        dark
        color="indigo"
        src="https://picsum.photos/1920/1080?random"
    >
        <template v-slot:img="{ props }">
            <v-img
                v-bind="props"
                gradient="to top right, rgba(19,84,122,.5), rgba(128,208,199,.8)"
            />
        </template>

        <v-toolbar-title>{{ appName }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <div v-if="authUser" class="d-flex">
            <a class="btn btn-light d-block mr-2" href="./admin">
                Admin
            </a>
            <div class="d-block">
                <form action="./admin/logout" method="POST">
                    <input type="hidden" name="_token" :value="csrf" />
                    <button class="btn btn-light">
                        Kijelentkezes
                    </button>
                </form>
            </div>
        </div>
    </v-app-bar>
</template>

<script>
export default {
    name: "Header",
    data() {
        return {
            appName: window.appName,
            authUser: window.auth_user,
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
        };
    }
};
</script>
