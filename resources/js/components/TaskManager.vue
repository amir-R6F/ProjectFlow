<template>
    <b-modal hide-header hide-footer size="xl" ref="taskManager">
        <b-overlay :show="loading" rounded="sm">
            <div class="gap-2" v-if="task">
                <b-breadcrumb>
                    <b-breadcrumb-item href="#home" v-for="(parent, i) in parents" :key="i"
                                       @click="openTask(parent.id)">
                        {{ $helper.cutLength(parent.title, 20)}}
                    </b-breadcrumb-item>
                </b-breadcrumb>

                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-8 p-1 space-y-4">
                        <div>
                            <button class="child-btn">
                                <b-icon icon="paperclip"></b-icon>
                                <span>attach file</span>
                            </button>

                            <button class="child-btn" @click="adding = true">
                                <b-icon icon="diagram2-fill"></b-icon>
                                <span>add child</span>
                            </button>

                            <button class="child-btn">
                                <b-icon icon="link45deg"></b-icon>
                                <span>link</span>
                            </button>
                        </div>

                        <!--                        <div class="my-1">-->
                        <!--                            <b-textarea rows="3" no-resize></b-textarea>-->
                        <!--                        </div>-->

                        <div>
                            <document-manager :data="task"></document-manager>
                        </div>


                        <div>
                            <div class="flex flex-col bg-slate-100 rounded-md text-sm p-2 gap-2">
                                <b-progress height="5px" animated max="100" variant="primary" :value="checker()">
                                </b-progress>

                                <div class="h-24 overflow-y-scroll myScroll">
                                    <div v-for="(child, i) in task.sub_task" :key="i"
                                         v-b-tooltip.hover :title="child.title"
                                         class="hover:bg-blue-200 hover:cursor-pointer p-1 rounded-md "
                                         @click="openTask(child.id)">
                                        {{ $helper.cutLength(child.title, 11)}}
                                    </div>
                                </div>

                                <transition name="slide-up">
                                    <div v-if="adding">
                                        <b-input placeholder="title" size="sm" v-model="child.title"></b-input>
                                        <div class="flex flex-row-reverse">
                                            <button class="add-btn"
                                                    @click="addChild">
                                                <b-icon icon="check" variant="white"></b-icon>
                                            </button>
                                            <button class="child-btn" @click="adding = false">
                                                <b-icon icon="x"></b-icon>
                                            </button>
                                        </div>
                                    </div>
                                </transition>

                            </div>
                        </div>
                    </div>

                    <div class="col-span-4 p-1 space-y-4">
                        <b-modal id="setRole" hide-footer hide-header class="p-3">
                            <div class="grid grid-cols-1 gap-2">
                                <h2 class="text-xl">Add People</h2>

                                <v-select :options="people" :reduce="user => user.id"
                                          label="name" v-model="form.members" multiple size="sm">
                                </v-select>

                                <v-select :options="roles" :reduce="role => role"
                                          label="name" v-model="form.role" size="sm">
                                </v-select>
                            </div>
                            <button class="child-btn">Cancel</button>
                            <button class="add-btn">Add</button>

                        </b-modal>

                        <div class="mt-1">
                            <button size="sm" class="child-btn" v-b-modal="'setRole'">
                                <b-icon icon="person-plus"></b-icon>
                                <span>add user</span>
                            </button>
                        </div>

                        <div class="bg-slate-100 rounded-md h-48 p-1 overflow-y-scroll myScroll my-1">
                            <div v-for="(i) in 10" :key="i" class="grid grid-cols-3 justify-items-stretch">
                                <b-avatar text="a"></b-avatar>
                                <span>user{{ i }}</span>
                                <b-icon icon="x" class="cursor-pointer justify-self-end"></b-icon>
                            </div>
                        </div>

                        <div>
                            <b-btn variant="warning">{{task.category.title}}</b-btn>
                            <v-select :options="categories" :reduce="category => category"
                                      label="name" v-model="category.id" size="sm">
                            </v-select>
                            <b-btn>add</b-btn>
                        </div>

                    </div>
                </div>
            </div>
        </b-overlay>
    </b-modal>
</template>

<script>
import event from "../event";

export default {
    name: "TaskManager",
    data() {
        return {
            task: null,
            child: {
                title: null,
                state: 1,
                dependent: false,
                parent_id: null,
                project_id: 1,

            },
            adding: false,
            loading: true,
            parents: [],
            people: [{name : "amir", id : 1},{name : "john", id : 2},{name : "mmd", id : 3}],
            roles: ["admin", "co-leader", "elder"],
            form:{
                members: [],
                role: null
            },
            category:{
                id:null,
                title:null
            }
        }
    },
    created() {

        event.$on("openTaskManager", (res) => {
            this.openTask(res);
            this.$refs['taskManager'].show()
        })
    },
    mounted() {
    },
    methods: {
        openTask(id) {
            this.resetTaskManager();
            axios.post("getTask", {id: id}).then(res => {
                this.task = res.data.task
                this.child.parent_id = this.task.id;
                this.child.project_id = this.task.project_id;
                this.category.id = this.task.category.id;
                this.category.title = this.task.category.title;
                this.parentChecker(res.data.task);
                this.loading = false;
            }).catch(e => {

            })
        },
        parentChecker(task) {
            this.parents.unshift({id: task.id, title: task.title})
            if (task.myparents)
                this.parentChecker(task.myparents)
        },
        resetTaskManager() {
            this.adding = false;
            this.task = null;
            this.parents = []
            this.loading = false;
            this.child.title = null
        },
        checker() {
            let val = 0;
            for (let i in this.task.sub_task) {
                if (this.task.sub_task[i].state == 6) {
                    val += 100 * (1 / this.task.sub_task.length);
                }
            }
            if (val == 100 && this.task.state != 6) {
                this.updateState(this.task.id)
            }
            return val;
        },
        updateState(data) {
            axios.post("updateTask", {id: data, state: 6}).then(res => {
                this.task.state = res.data.req.state;
                // this.addChild(this.task)
            }).catch(e => {
                console.log(e)
            })
        },
        addChild() {
            axios.post("addTask", this.child).then(res => {
                this.openTask(this.task.id);
            }).catch(e => {
                console.log(e)
            })
        },
    }

}
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease-out;
}

.slide-up-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.slide-up-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>
