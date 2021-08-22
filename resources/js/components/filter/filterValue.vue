<template>
    <v-col cols="6" sm="4" md="5" class="pt-0 pb-0">
        <v-text-field
            dense
            name="value"
            :value="value"
            @input="$emit('input', $event)"
        ></v-text-field>
    </v-col>
</template>

<script>
export default {
    props: ["value"]
};
</script>

<style scoped></style>
