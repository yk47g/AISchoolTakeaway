<template>
  <section class="index-container">
    <div
      class="index-shopInfo"
      @click="clickToRest(restaurant)"
    >
      <!-- 左侧图片 -->
      <div class="logo_container">
        <img
          :src="'https://takeawayschool.oss-cn-shenzhen.aliyuncs.com/restImgs/'+restaurant.logo"
          alt
        >
      </div>
      <!-- 右侧内容 -->
      <div class="index_main">
        <!-- 第一行 店铺名字 -->
        <div class="index_shopname">
          <span class="shopname">{{ restaurant.name }}</span>
        </div>

        <!-- 第二行 星级 -->
        <div class="index-rateWrap">
          <div>
            <Rating :rating="parseFloat(restaurant.stars)" />
            <span class="rate">{{ restaurant.stars }}</span>
            <span>月售{{ restaurant.salesNum }}单</span>
          </div>
        </div>

        <!-- 第三行 配送 -->
        <div class="index-moneylimit">
          <div>
            <!--             <span>¥10起送</span>
            |-->
            <span v-if="restaurant.status=='1'">配送费¥{{ restaurant.deliveryFee }}起</span>
            <span v-else>此店铺已打烊</span>
          </div>
          <div class="index-distanceWrap">
            <span class="spans">
              <van-icon name="shop-o" />
              第{{ restaurant.roomNum }}饭堂
            </span>
            <!--             |
            <span>平均1分钟</span>-->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Rating from "./Rating";
import { Toast } from 'vant';
export default {
  name: "IndeShop",
  components: {
    Rating
  },
  props: {
    restaurant: {
      type: Object,
      default: () => {}
    }
  },
  methods:{
    clickToRest(restaurant){
      if (restaurant.status=='1') {
        this.$router.push({name: 'shop',params: {restID: restaurant.id}});
      }else{
        Toast.fail('抱歉，此店铺已经打烊了哦');
      }
      
    }
  }
};
</script>

<style scoped>
.index-container {
  background: #fff;
  color: #666;
  padding: 4vw 0;
  border-bottom: 0.133333vw solid #eee;
}
.shopname {
  font-size: 0.95rem;
}
span {
  font-size: 0.7rem;
}
.index-shopInfo {
  display: flex;
  justify-content: flex-start;
  padding: 0 2.666667vw;
  align-items: stretch;
}
.logo_container {
  width: 17.333333vw;
  height: 17.333333vw;
}
.logo_container img {
  display: block;
  width: 100%;
  height: 100%;
  box-sizing: border-box;
  border: 0.133333vw solid rgba(0, 0, 0, 0.08);
  border-radius: 0.533333vw;
}
.index_main {
  display: flex;
  justify-content: space-between;
  overflow: hidden;
  flex-direction: column;
  padding-left: 2.666667vw;
  font-size: 0.2rem;
  flex-grow: 1;
}
.index_shopname {
  align-items: center;
  color: #333;
  font-weight: 700;
  font-size: 0.9rem;
}
.index_shopname span {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.index-rateWrap {
  display: flex;
  align-items: center;
  overflow: hidden;
  justify-content: space-between;
}

.index-rateWrap .rate {
  margin-right: 1.066667vw;
}
.index-moneylimit {
  width: 100%;
  display: flex;
  justify-content: space-between;
}
.index-moneylimit .index-distanceWrap {
  color: #999;
}
.spans {
  display: flex;
}
</style>
