<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" persistent max-width="290">
            <template v-slot:activator="{ on, attrs }">
                <v-icon v-bind="attrs" v-on="on" small color="red">
                    mdi-delete
                </v-icon>
            </template>
            <v-card>
                <v-card-title
                    class="text-h5 red darken-2 text-center text-white"
                >
                    Valóban törli?
                </v-card-title>
                <v-card-text class="pt-2">
                    {{ title }}
                </v-card-text>
                <v-card-actions>
                    <v-btn color="green darken-1" text @click="dialog = false">
                        Nem
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="
                            () => {
                                dialog = false;
                                this.onDelete();
                            }
                        "
                    >
                        Igen
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    props: ["title", "onDelete"],
    data() {
        return {
            dialog: false
        };
    }
};
</script>
