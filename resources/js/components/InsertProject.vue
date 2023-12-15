<template>
    <div>
        <b-modal ref="createProject" hide-footer size="sm">
            <template #modal-header>
                <div class="w-full bg-sky p-1 rounded-md">
                    <h1 class="text-2xl text-white">Project</h1>
                </div>
            </template>

            <div class="grid grid-cols-1 gap-2 justify-items-stretch">
                <b-input class="rounded-sm" size="sm" v-model="form.title" placeholder="Project Title"></b-input>

                <b-datepicker class="" size="sm" v-model="form.dueDate"></b-datepicker>

                <v-select v-if="friends" size="sm" :options="friends" :reduce="user => user.id"
                          label="name"
                          v-model="form.members"
                          class="border-cyan-500 rounded-sm"
                          :components="{Deselect}" multiple>
                </v-select>

                <b-btn class=" test w-25 justify-self-end" pill @click="addProject">Add</b-btn>

            </div>

        </b-modal>
    </div>
</template>

<script>
import event from "../event";

export default {
    name: "InsertProject",
    data() {
        return {
            friends: [{name : "amir", id : 1}],
            form: {
                id: null,
                title: null,
                admin: Laravel.user.id,
                members: [],
                state: 'running',
                dueDate: null,
            },
            Deselect: {
                render: createElement => createElement('span', '❌'),
            },
        }
    },
    methods: {
        addProject() {
            (async () => {
                let result = await this.$helper.store('insertProject', this.form);
                console.log(result)
            })();
        },
    },
    created() {
        event.$on("openInsertProject", () => {
            this.$refs["createProject"].show();
        })

    }
}
</script>

<style scoped>

.test {
    background: #fdc04e;
    background: -webkit-linear-gradient(to right, #fdc04e, #f94e45);
    background: linear-gradient(to right, #fdc04e, #f94e45);
    border: none;
}
</style>
