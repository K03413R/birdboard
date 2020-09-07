<template>
    <div class="dropdown relative">
        <div
            @click="isOpen = !isOpen"
            class="dropdown-toggle"
            aria-haspopup="true"
            :aria-expanded="isOpen"
        >
            <slot name="trigger"></slot>
        </div>

        <div
            class="absolute dropdown-menu bg-white py-2rounded mt-2 shadow"
             v-if="isOpen"
            :class="align === 'left' ? 'left-0' : 'right-0'"
            :style="{ width }"
        >
            <slot></slot>
        </div>
    </div>
</template>
<script>
 export default {
     props:{
         width: {default: 'auto'},
         align: {default: 'left'}
     },
     data() {
         return {
             isOpen: false
         }
     },
     watch:{
         isOpen(isOpen){
             if(isOpen){
                 document.addEventListener('click',  this.closeIfClickedOutside);
             }
         }
     },
     methods:{
         closeIfClickedOutside(event){
             if(!event.target.closest('.dropdown')){
                 this.isOpen = false;
                 document.removeEventListener('click',  this.closeIfClickedOutside);
             }
         }
     }
 }
</script>
