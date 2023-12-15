<template>
    <div class="flex flex-col p-2 text-sm text-gray-700 bg-slate-100 rounded-md scroll-ml-6 snap-end w-full ">
        <div class="flex flex-row justify-between">
            <h2 class="text-sm">file attached</h2>

            <button @click="storeDocument" class="upload-btn" v-if="documents.length">
                <b-icon icon="cloud-upload-fill" class="animate-bounce"></b-icon>
                <span>upload</span>
            </button>

            <input
                type="file"
                ref="file"
                hidden
                multiple
                id="actual-btn"
                @change="check">
            <label for="actual-btn">
                <b-icon icon="paperclip"></b-icon>
                <span>attachFile</span>
            </label>
        </div>

        <div class="grid grid-rows-1 grid-flow-col gap-4 p-2 overflow-x-scroll w-full snap-x myScroll ">

            <div class="relative w-40 rounded-lg overflow-hidden" v-for="(myFile, i) in documents">
                <img v-if="myFile.pic" :src="myFile.pic" class="h-32 w-full object-fill">

                <b-iconstack font-scale="8" v-else>
                    <b-icon shift-h="4" shift-v="-2" stacked :icon="myFile.icon" ></b-icon>
                </b-iconstack>

                <div class="overlay-box2">
                    <div></div>
                    <div>
                        <button @click="documents.splice(i, 1)" class="overylay-btn">
                            <b-icon icon="trash-fill" variant="dark"></b-icon>
                        </button>
                    </div>
                    <div></div>
                </div>
            </div>

            <div v-if="clone !== null" v-for="(doc, i) in clone.document" :key="i">
                <div class="relative w-40 rounded-lg overflow-hidden">
                    <img v-if="doc.mime.includes('image')" :src="'/storage/asset/'+doc.system_name" class="h-32 w-full object-fill">

                    <b-iconstack font-scale="8" v-else>
                        <b-icon shift-h="4"  stacked :icon="SVG(doc)"></b-icon>
                    </b-iconstack>

                    <div class="overlay-box">
                        <div></div>
                        <div></div>
                        <div>
                            <button @click="deleteDocument(doc)" class="overylay-btn">
                                <b-icon icon="trash-fill" variant="dark"></b-icon>
                            </button>
                        </div>
                        <div class="place-self-end">
                            <button @click="downloadDocument(doc)" class="overylay-btn ">
                                <b-icon icon="cloud-download-fill" variant="dark"></b-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import event from "../../event";

export default {
    props: ['data'],
    name: "DocumentManager",
    data() {
        return {
            clone: this.data,
            documents: [],
        }
    },
    methods: {
        storeDocument() {
            if (this.documents) {
                let formData = new FormData();
                this.documents.forEach(doc => {
                    formData.append("documents[]", doc.file)
                })
                formData.append('task_id', this.clone.id);

                axios.post('insertDocument', formData,
                    {headers: {'Content-Type': 'multipart/form-data'}}).then((res) => {
                    console.log(res)
                }).catch((e) => {
                    console.log(e)
                })
            }

        },
        deleteDocument(doc) {
            (async () => {
                console.log(doc)
                let result = await this.$helper.mydelete('deleteDocument', doc);
                this.$helper.deleteFromRepository(result.id, this.clone.document);
            })();
        },
        downloadDocument(doc) {
            axios.post('downloadDocument', doc).then(res => {
                console.log(res)
                let blob = new Blob([res.data]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = doc.original_name;
                link.click();
                link.remove();
            })
        },
        check() {
            let input = this.$refs.file;

            for (let i = 0; i < input.files.length; ++i) {
                if (input.files[i].type.includes('image')) {
                    let Reader = new FileReader();
                    Reader.onload = (res) => {
                        this.documents.push({file: input.files[i], pic: res.target.result})
                    }
                    Reader.readAsDataURL(input.files[i]);
                } else {
                    console.log(input.files[i].type)
                    this.documents.push({file: input.files[i], icon: this.SVG(input.files[i].type)})
                }
            }
        },
        SVG(doc) {
            let data;
            doc.mime ? data = doc.mime : data = doc;
            let permitted_files = [
                {type: "pdf", icon: 'file-earmark-pdf-fill', color: 'text-red-500'},
                {type: 'word', icon: 'file-earmark-word-fill', color: 'text-blue-600'},
                {type: 'x-zip', icon: 'file-earmark-zip-fill', color: 'text-green-600'},
                {type: 'text', icon: 'file-earmark-text-fill', color: 'text-red-800'},
            ];

            for (let i in permitted_files) {
                if (data.includes(permitted_files[i].type)) {
                    return permitted_files[i].icon;
                }else{
                    return "question-square-fill";
                }
            }
        },
    },
    created() {
    }
}
</script>

<style scoped>

label {
    background-color: indigo;
    color: white;
    padding: 0.5rem;
    font-family: sans-serif;
    border-radius: 0.3rem;
    cursor: pointer;
    scale: 0.75;
}


</style>
