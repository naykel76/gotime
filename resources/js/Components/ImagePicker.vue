 <template>
  <div class="fullwidth">

    <img id="img_output" v-bind:src="path">

    <div class="mt">

      <div class="btn-success mr file" :class="componentClasses">

        <input type="file" name="image_path" accept="image/*" @change="onChange" id="xx" />

        <svg class="icon">
          <use xlink:href="/svg/nk_icon-defs.svg#icon-upload"></use>
        </svg>

        <span>Select Image</span>

      </div>

      <div v-on:click="clear" class="btn-danger outline">Clear Image</div>
    </div>

  </div>
</template>

<script>
export default {
  props: ["classes", "imagePath"],
  data() {
    return {
      componentClasses: this.classes,
      path: this.imagePath,
    };
  },
  methods: {
    onChange(e) {
      if (!e.target.files.length) return;

      let file = e.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(file);
      // once the file has loaded (could be a large file)
      reader.onload = (e) => {
        // data source of the image
        let src = e.target.result;
        // preview the image using the data source
        let image = document.getElementById("img_output");
        image.src = src;
      };
    },
    clear() {
      let file = document.getElementById("xx");
      if (file.files.length == 0) {
        console.log("no file");
      } else {
        console.log("has file");
      }

      // clear the preview window
      let image = document.getElementById("img_output");
      // set place holder
      image.src = "/svg/placeholder.svg";

      document.getElementById("deleteImage").value = true;
    },
  },
};
</script>

