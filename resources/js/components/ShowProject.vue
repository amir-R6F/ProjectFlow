<template>
    <div v-show="display">

        <button @click="back" class="bg-white">
            <b-icon icon="arrow-left-square-fill" variant="danger" font-scale="1.5"></b-icon>
        </button>

        <div v-if="project" class="text-white">

            <b-modal ref="createTask" hide-footer size="sm">
                <template #modal-header>
                    <div class="w-full bg-sky p-1 rounded-md">
                        <h1 class="text-lg text-white">
                            Add Task
                        </h1>
                    </div>
                </template>
                <insert-task :u_list="project.members_list" :p_id="project.id"></insert-task>
            </b-modal>

            <div class="flex flex-row justify-between">
                <div>
                    <button @click="$refs['createTask'].show()" class="add-btn ">
                        <b-icon icon="diagram3-fill"></b-icon>
                        <span>add Task</span>
                    </button>
                </div>

                <div>
                    <button class="filter-btn" @click="filter.dropdown = !filter.dropdown">
                        {{ filter.active }}
                    </button>
                    <ul :class="['filter-box', filter.dropdown ? 'max-h-96' : 'max-h-0 invisible']">
                        <li class="filter-box-item" v-for="(obj, i) in filter.filters" :key="i" v-text="obj"
                            @click="groupBy(obj)"></li>
                    </ul>
                </div>
            </div>

            <context-menu></context-menu>

            <task-manager></task-manager>

            <!--            <div class="grid grid-rows-1 grid-flow-col gap-4 p-2 overflow-x-scroll w-full snap-x myScroll">-->
            <!--                <task-project v-for="(obj, i) in tasks_clone" :key="obj.id"-->
            <!--                              :p_task="obj"-->
            <!--                              :addChild="addNewChild"-->
            <!--                              :delChild="deleteChild"-->
            <!--                ></task-project>-->
            <!--            </div>-->

            <div class="grid grid-cols-12">
                <div v-for="(cat, i) in categories" :key="i" class="relative my-20 col-span-3">
                    <div @click="cat.active = !cat.active"
                         :class="['bg-blue-500 absolute rounded-md -top-5 p-1 bg-orange-500 border-2 cursor-pointer text-center transition-all duration-500', cat.active ? 'left-1' : 'inset-x-0']">
                        {{ cat.title }}
                    </div>
                    <div
                        :class="['flex flex-col overflow-y-scroll space-y-4 w-full myScroll rounded-lg text-black transition-all duration-500', cat.active ? 'max-h-64 box p-2' : 'max-h-0 border-2']">
                        <task-project v-for="(obj, i) in cat.task" :key="obj.id"
                                      :p_task="obj"
                                      :addChild="addNewChild"
                                      :delChild="deleteChild"
                        ></task-project>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import event from "../event";
import InsertTask from "./InsertTask";

export default {
    name: "ShowProject.vue",
    components: {InsertTask},
    data() {
        return {
            display: false,
            filter: {
                dropdown: false,
                filters: ["children", "parents", "none"],
                active: "none"
            },
            client: Laravel.user.id,
            project: null,
            categories: [],
            tasks_clone: []
        }
    },
    created() {

        event.$on('explore', (res) => {
            this.displayDesktop();
            this.getTasks(res);
            this.getCategories(res);
        })

        event.$on('pushTask', (res) => {
            this.tasks_clone.push(res.taskData)
        })

    },
    methods: {
        back() {
            this.displayDesktop();
            event.$emit("Back");
        },
        getTasks(project_id) {
            axios.post("getProject", {id: project_id}).then(res => {
                this.project = res.data.project;
                this.tasks_clone = res.data.project.task;
            }).catch(e => {
            })
        },
        getCategories(project_id) {
            axios.post("getCategories", {id: project_id}).then(res => {
                this.categories = res.data.categories;
            }).catch(e => {
                console.log(e)
            })
        },
        displayDesktop() {
            // this.project = null;
            // this.tasks_clone = [];
            this.filter.dropdown = false;
            this.display = !this.display
        },
        addNewChild(data) {
            this.tasks_clone.push(data)
        },
        deleteChild(data) {
            this.tasks_clone = this.tasks_clone.filter((obj) => {
                return data.id != obj.id
            })
        },
        groupBy(filterType) {
            this.filter.active = filterType;
            this.filter.dropdown = false;
            this.tasks_clone = this.project.task.filter((obj) => {
                if (filterType == "children")
                    return obj.parent_id != null
                else if (filterType == "parents")
                    return obj.parent_id == null
                else
                    return obj
            })
        }

    },
}
</script>

<style scoped>
.box {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    /*backdrop-filter: blur( 20px );*/
    border: 1px solid rgba(255, 255, 255, 0.18);
}

</style>
