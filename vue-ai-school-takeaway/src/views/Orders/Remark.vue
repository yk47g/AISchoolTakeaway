<template>
  <div class="remark">
    <div class="header">
      <van-nav-bar
        title="确认订单"
        left-arrow
        @click-left="$router.go(-1)"
      />
    </div>
    <!-- 订单备注 -->
    <div class="view-body">
      <section>
        <textarea
          v-model="text"
          placeholder="填写额外对餐厅或骑手小哥备注的信息"
          maxlength="100"
        />
        <div class="switch">
          <span
            v-for="(item,index) in radioItem"
            :key="index"
            :class="{'selected': item.select}"
            class="switch-item item-line"
            @click="handleRadioItem(item)"
          >{{ item.name }}</span>
        </div>
        <div
          v-for="(item,index) in switchItem"
          :key="index"
          class="switch"
        >
          <span
            :class="{'selected': item.select}"
            class="switch-item"
            @click="item.select = !item.select"
          >{{ item.name }}</span>
        </div>
      </section>
      <button
        class="btn-submit"
        @click="submitClick"
      >
        确定
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Remark",
  data() {
    return {
      radioItem: [
        { select: false, name: "不要辣" },
        { select: false, name: "少点辣" },
        { select: false, name: "多点辣" }
      ],
      switchItem: [
        { select: false, name: "不要香菜" },
        { select: false, name: "不要洋葱" },
        { select: false, name: "多点醋" },
        { select: false, name: "多点葱" }
      ],
      text: ""
    };
  },
  methods: {
    handleRadioItem(item) {
      this.radioItem.forEach(element => {
        element.select = false;
      });
      item.select = true;
    },
    submitClick() {
      let selectItems = "";
      this.radioItem.forEach(element => {
        if (element.select) {
          selectItems += element.name + ",";
        }
      });

      this.switchItem.forEach(element => {
        if (element.select) {
          selectItems += element.name + ",";
        }
      });

      selectItems += this.text;
      // console.log(selectItems);
      // 存储
      this.$store.dispatch("setRemarkInfo", {
        tableware: this.$store.getters.remarkInfo.tableware,
        remark: selectItems
      });

      this.$router.go(-1);
    }
  },
};
</script>

<style scoped>
.remark {
  width: 100%;
  height: 100%;
  box-sizing: border-box;
}

.view-body {
  width: 100%;
  height: 100%;
  overflow: auto;
  box-sizing: border-box;
  font-size: 0.8rem;
  color: #333;
}
.view-body section {
  margin-top: 2.133333vw;
  padding: 4.266667vw;
  background-color: #fff;
  margin-bottom: 2.133333vw;
}
.view-body section textarea {
  width: 100%;
  height: 29.866667vw;
  margin-bottom: 4.266667vw;
  padding: 4.266667vw;
  border: 1px solid #ccc;
  border-radius: 0.666667vw;
  background-color: #f9f9f9;
  color: #666;
  resize: none;
  box-sizing: border-box;
  outline: none;
}
.switch {
  display: inline-block;
  margin: 0 2.666667vw 2.666667vw 0;
  border: 1px solid #ddd;
  overflow: hidden;
  border-radius: 1.066667vw;
}
.switch-item {
  display: inline-block;
  padding: 0 2.133333vw;
  height: 8vw;
  line-height: 8vw;
  text-align: center;
  color: #666;
}
.item-line::after {
  content: " ";
  display: inline-block;
  height: 4vw;
  width: 1px;
  background: #ddd;
  line-height: 8vw;
  vertical-align: middle;
  left: 2.266667vw;
  position: relative;
}
.btn-submit {
  display: block;
  padding: 3.466667vw 0;
  color: #fff;
  background-color: #00e067;
  border-radius: 0.533333vw;
  opacity: 0.98;
  width: 92vw;
  margin: 3.133333vw auto 0;
  font-size: 1rem;
}

/* 选中样式 */
.switch-item.selected {
  background: #0186ff;
  color: #fff;
  position: relative;
}
</style>
