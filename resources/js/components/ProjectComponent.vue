<template>
    <div v-show="display">

        <b-button @click="createProject" variant="primary">
            <b-icon icon="diagram3"></b-icon>
            <span>add project</span>
        </b-button>

        <insert-project :all_friend="friends" :all_contact="contacts"></insert-project>

        <div class="row justify-content-center">
            <b-card class="glass mx-4 my-4 col-md-5 col-sm-12 col-lg-3" body-class="text-center"
                    v-for="(projects) in myProjects" :key="projects.id">

                <b-card-title>
                    <progress-bar :data="projects.task" :state="6" :col="'state'"></progress-bar>
                </b-card-title>

                <b-card-body>
                    <b-card-text v-text="projects.title" class="mt-5">
                    </b-card-text>
                    <b-card-text
                        v-text="new Date(projects.updated_at, 'YYYY-MM-DD')" class="text-secondary">
                        <!--                Updated 19 Aug 20-->
                    </b-card-text>

                    <b-button variant="primary" @click="explore(projects)">Explore</b-button>
                </b-card-body>


            </b-card>
        </div>
    </div>
</template>

<script>
import event from "../event";

export default {
    name: "ProjectComponent.vue",
    data() {
        return {
            display: true,
            myProjects: null,
            friends: null,
            contacts: null,
        }
    },
    methods: {
        displayDesktop() {
            this.display = !this.display
        },
        explore(data) {
            event.$emit('explore', data.id)
            this.displayDesktop()
        },
        getProjects() {
            axios.get("getProjects").then(res => {
                this.myProjects = res.data.projects;
            }).catch(e => {
            })
        },
        createProject() {
            event.$emit("openInsertProject");
        }
    },
    created() {
        this.getProjects();

        event.$on("Back", () => {
            this.displayDesktop();
        })
    }
}
</script>

<style scoped>
.glass {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    /*backdrop-filter: blur( 20px );*/
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.drop-area {
    border: 10px dashed #8a8a8a;
}
</style>
