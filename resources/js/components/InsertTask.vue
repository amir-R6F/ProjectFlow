<template>
    <div>

<!--            <template #modal-header>-->
<!--                <div class="w-full bg-sky p-1 rounded-md">-->
<!--                    <h1 class="text-lg text-white">-->
<!--                        Create {{ t_id ? "Child" : "Project" }} Task-->
<!--                    </h1>-->
<!--                </div>-->
<!--            </template>-->

            <div>
                <validation-observer ref="observe" v-slot="{handleSubmit}">
                    <b-form @submit.stop.prevent="handleSubmit(submit)" class="grid grid-cols-1 gap-2">

                        <validation-provider
                            name="task"
                            rules="minmax:1,150|required"
                            v-slot="validationContext"
                        >
                            <b-form-group>
                                <b-input
                                    v-model="form.title"
                                    size="sm"
                                    :state="getValidationState(validationContext)"
                                    aria-describedby="feedback1"
                                    placeholder="Task Title"
                                ></b-input>
                                <b-form-invalid-feedback id="feedback1">{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="subject"
                            rules="minmax:1,150|required"
                            v-slot="validationContext"
                        >
                            <b-form-group>
                                <b-textarea
                                    v-model="form.subject"
                                    size="sm"
                                    aria-describedby="feedback2"
                                    :state="getValidationState(validationContext)"
                                    placeholder="About Your Task"
                                    rows="5"

                                ></b-textarea>
                                <b-form-invalid-feedback id="feedback2">{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="date"
                            rules="required"
                            v-slot="validationContext"
                        >
                            <b-form-group>
                                <b-datepicker
                                    v-model="form.dueDate"
                                    size="sm"
                                    aria-describedby="feedback3"
                                    :state="getValidationState(validationContext)"
                                ></b-datepicker>
                                <b-form-invalid-feedback id="feedback3">{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <validation-provider
                            name="time"
                            rules="required"
                            v-slot="validationContext"
                        >
                            <b-form-group>
                                <b-timepicker
                                    v-model="time"
                                    size="sm"
                                    aria-describedby="feedback4"
                                    :state="getValidationState(validationContext)"
                                ></b-timepicker>
                                <b-form-invalid-feedback id="feedback4">{{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>

                        <v-select :options="users" :reduce="user => user.id" label="name"
                                  v-model="form.user_list"
                                  size="sm"
                                  class="my-2 glass" multiple>
                        </v-select>


                        <label for="depend">
                            <input type="checkbox" id="depend" v-model="form.dependent">
                            </input>
                            Depend on their children
                        </label>


                        <b-btn class="insert-btn" type="submit"> add </b-btn>
                    </b-form>
                </validation-observer>
            </div>

    </div>
</template>

<script>
import event from "../event";

export default {
    name: "InsertTask",
    props: ['p_id', 't_id', 'u_list'],
    data() {
        return {
            form: {
                project_id: this.p_id,
                parent_id: this.t_id,
                title: null,
                subject: null,
                dueDate: null,
                state: 1,
                dependent: false,
                user_list: [],
            },
            users: [],
            time: null,
        }
    },
    methods: {
        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        },
        submit() {
            this.form.dueDate = this.form.dueDate.concat(" ", this.time);
            (async () => {
                let result = await this.$helper.store('addTask', this.form)
                event.$emit('pushTask', result)
                // this.$refs["createTask"].hide();
            })();
        }
    },
    created() {
        if (this.t_id) {
            this.form.parent_id = this.t_id;
        }
    },

    mounted() {
        if (this.t_id) {
            axios.post('userList', {id: this.t_id}).then(res => {
                this.users = res.data.list;
            }).catch(e => {
                console.log(e)
            })
        } else {
            this.users = this.u_list;

        }
    }
}
</script>

<style scoped>
.insert-btn {
    background: rgb(180, 58, 155);
    background: linear-gradient(90deg, rgba(180, 58, 155, 1) 0%, rgba(119, 87, 171, 1) 100%);
}
</style>
