<template>
  <div class="order">
    <van-pull-refresh
      v-if="!firstlogin"
      v-model="isLoading"
      @refresh="onRefresh"
    >
      <van-list
        v-model="loading"
        :finished="allLoaded"
        finished-text="没有更多了"
        @load="loadMore"
      >
        <div
          v-for="(order,index) in orders"
          :key="index"
          class="order-card-body"
        >
          <div
            v-if="order"
            class="order-card-wrap"
            @click="toOrderInfo(order)"
          >
            <img
              :src="(order.restLogo)?('https://takeawayschool.oss-cn-shenzhen.aliyuncs.com/restImgs/'+order.restLogo):('https://takeawayschool.oss-cn-shenzhen.aliyuncs.com/icontyexpress.png')"
              alt
            >
            <div class="order-card-content">
              <div class="order-card-head">
                <div class="title">
                  <a>
                    <span>{{ (order.restName)?(order.restName):('快递代拿') }}</span>
                    <van-tag
                      v-if="order.isNeedFast == 1"
                      round
                      type="danger"
                      plain
                      style="margin-left:5px;text-align: center;"
                    >
                      加急 +{{ order.fastMoney }} 元
                    </van-tag>
                    <van-tag
                      v-if="order.isNeedFast == 2"
                      round
                      type="success"
                      plain
                      style="margin-left:5px;text-align: center;"
                    >
                      加急 {{ order.fastMoney }} 元 已退回
                    </van-tag>
                  </a>
                  <p style="display:flex;">
                    <van-icon
                      v-show="order.statusText=='待支付尾款'"
                      name="warning"
                      style="padding-right:2px;color:red;"
                    />{{ order.statusText }}
                  </p>
                </div>
                <p class="date-time">
                  {{ order.createTime }}
                </p>
              </div>
              <div class="order-card-detail">
                <p
                  class="detail"
                >
                  {{ (order.showfood)?(order.showfood):(order.expressAddr+'快递点 '+order.goodType+'类') }}
                </p>
                <p class="price">
                  ¥{{ (parseFloat(order.payPrice)/100).toFixed(2) }}
                </p>
              </div>
            </div>
          </div>
          <div
            v-show="(order.status>=4 || order.status==0) && order.type!=1"
            class="order-card-bottom"
          >
            <button
              class="cardbutton"
              @click="$router.push({name: 'shop',params: {restID: order.restID}})"
            >
              再来一单
            </button>
          </div>
        </div>
      </van-list>
    </van-pull-refresh>
    <div
      v-if="firstlogin"
      class="nologin"
    >
      <NoLoginInfo />
    </div>
    <div
      v-if="nodata"
      class="nologin"
    >
      <NoDeliveOrderInfo />
    </div>
  </div>
</template>

<script>
import NoLoginInfo from "../components/NoLoginInfo";
import NoDeliveOrderInfo from "../components/NoDeliveOrderInfo";
export default {
  name: "Order",
  components: {
    NoLoginInfo,
    NoDeliveOrderInfo
  },
  data() {
    return {
      isLoading: false,
      firstlogin: false,
      nodata: false,
      orderlist: [], //存放当前订单容器
      offset: 1,
      size: 5,
      allLoaded: false,
      loading: false,
      orders: [] //存放所有订单容器
    };
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.getData();
    });
  },
  computed: {
    userInfo() {
      return this.$store.getters.userInfo;
    }
  },
  mounted() {
    // 监听手机物理返回键时禁止返回之前的路由
    if (window.history && window.history.pushState) {
      window.addEventListener("popstate", this.forbidBack, false);
      this.forbidBack();
    }
  },
  methods: {
    onRefresh() {
      this.orders = [];
      this.orderlist = [];
      this.allLoaded = false;
      this.loading = true;
      //this.firstLoadData();
      this.offset = 0;
      this.loadMore();
      this.isLoading = false;
    },
    getData() {
      this.firstlogin = localStorage.firstlogin == 0 ? false : true;
      this.offset = 0;
      this.allLoaded = false;
      //this.firstLoadData();
    },
/*     firstLoadData() {
      this.offset = 1;
      this.allLoaded = false;
      // 拉取商家信息
      this.$axios("https://takeawayapi.pykky.com/?s=Orders.GetOnesAllOrders", {
        params: {
          userID: this.userInfo.id,
          page: this.offset,
        }
      }).then(res => {
        if (JSON.stringify(res.data.data) == "{}") {
          this.allLoaded = true;
          this.nodata = true;
          this.loading = false;
          return;
        }
        this.orderlist = res.data.data;
        this.handleData();
      });
    }, */
    loadMore() {
      // 异步更新数据
      setTimeout(() => {
        if (!this.allLoaded) {
          //this.offset += parseInt(this.orders[0].id);
          this.offset++;
          // 拉取商家信息
          this.$axios("https://takeawayapi.pykky.com/?s=Orders.GetOnesAllOrders", {
            params: {
              userID: this.userInfo.id,
              page: this.offset,
            }
          }).then(res => {
            if (JSON.stringify(res.data.data) != "{}") {
              this.orderlist = res.data.data;
              this.handleData();
              this.loading = false;
              if (res.data < this.size) {
                this.allLoaded = true;
                this.loading = false;
              }
            } else {
              // 数据为空
              this.allLoaded = true;
              this.loading = false;
            }
          });
        }
      }, 500);
    },
    handleData() {
      //对商品数据处理
      this.orderlist.forEach(order => {
        if (order.type == 0) {
          //仅处理美食跑腿
          var showfood = "";
          var OrderFoods = JSON.parse(order.foods);
          var foodsArr = new Array();
          OrderFoods.forEach(id => {
            order.food.forEach(food => {
              if (food.id == id) {
                foodsArr.push(food); //把每行food的全部数据对象都放入数组
              }
            });
          });
          order.foodsArr = foodsArr;
          var o = 0;
          foodsArr.forEach(food => {
            if (o < 2) {
              showfood += " " + food.name;
            }
            o++;
          });
          if (o > 2) {
            showfood += " 等";
          }
          order.showfood = showfood;
        }

        //状态提示文字
        order.statusText = null;
        const status = parseInt(order.status);
        switch (status) {
          case 0:
            order.statusText = "未支付";
            break;
          case 1:
            order.statusText = "待接单";
            break;
          case 2:
            order.statusText = "待取货";
            break;
          case 3:
            order.statusText = "配送中";
            break;
          case 4:
            if (order.type==1) {
              order.statusText = "已完成";
            }else{
              order.statusText = "待评价";
            }
            break;
          case 5:
            order.statusText = "已评价";
            break;
          case 6:
            order.statusText = "已回复评价";
            break;
          case 7:
            order.statusText = "退款中";
            break;
          case 8:
            order.statusText = "已关闭";
            break;
          case 9:
            order.statusText = "待支付尾款";
            break;
          default:
            order.statusText = "订单异常";
            break;
        }
        this.orders.push(order);
      });
    },
    toOrderInfo(order) {
      var toData = {};
      toData.restName = order.restName;
      toData.createTime = order.createTime;
      toData.selectFoods = order.foodsArr;
      toData.payPrice = order.payPrice;
      toData.deliveryFee = order.deliveFee;
      toData.expressAddr = order.expressAddr;
      toData.expressCode = order.expressCode;
      toData.goodType = order.goodType;
      toData.isNeedFast = order.isNeedFast;
      toData.fastMoney = order.fastMoney;
      //处理重量
      let weightNum = parseInt(order.weight);
      switch (weightNum) {
        case 0:
          toData.weight = "小(<1.5kg 约3瓶中型怡宝)";
          break;
        case 1:
          toData.weight = "中(>1.5-3kg 约1瓶大型怡宝)";
          break;
        case 2:
          toData.weight = "大(>3kg-5kg 约1箱牛奶)";
          break;
        case 3:
          toData.weight = "特大(>5kg-10kg 约2箱牛奶)";
          break;
        case 4:
          toData.weight = "其他(>10kg/体积大)";
          break;
        default:
          break;
      }
      //切记传值前一定要先建立对应属性为null！！！！！
      //toData.deliveryFee = null;
      toData.value = null;
      toData.addrInfo = null;
      /* //店铺配送费信息
      this.$axios("https://takeawayapi.pykky.com/?s=Restaurant.GetOneRest", {
        params: {
          id: order.restID
        }
      }).then(res => {
        toData.deliveryFee = res.data.data.deliveryFee;
      }); */
      //收货地址信息
      this.$axios("https://takeawayapi.pykky.com/?s=Address.GetOneAddr", {
        params: {
          id: order.addressID
        }
      }).then(res => {
        toData.addrInfo = res.data.data;
      });
      //红包信息
      if (order.discountID != -1) {
        this.$axios("https://takeawayapi.pykky.com/?s=Discount.GetOnesDiscounts", {
          params: {
            id: order.discountID
          }
        }).then(res => {
          toData.value = res.data.data.value;
        });
      } else {
        toData.value = "0";
      }
      //订单备注信息
      toData.remark = order.remark;
      toData.status = order.status;
      toData.shouldDeliveTime = order.shouldDeliveTime;
      toData.id = order.id;
      toData.hasComplaint = order.hasComplaint;
      toData.restID = order.restID;
      if (order.status >= 2) {
        toData.deliverName = order.deliverName;
        toData.delivedTime = order.delivedTime;
        toData.deliverPhone = order.deliverPhone;
        toData.deliverID = order.deliverID;
      }
      this.$router.push({ name: "orderInfo", params: toData });
    },
    forbidBack() {
      window.history.pushState("forward", null, "#");
      window.history.forward(1);
    }
  },
  destoryed() {
    // 离开页面时销毁监听
    window.removeEventListener("popstate", this.forbidBack, false);
  }
};
</script>

<style scoped>
.order {
  width: 100%;
  height: 100%;
  overflow: auto;
  box-sizing: border-box;
  margin-bottom: 2.666667vw;
}
.nologin {
  height: 100%;
  display: flex;
  align-items: center;
}
.order-card-body {
  margin-top: 2.666667vw;
  background-color: #fff;
  padding: 3.733333vw 0 0 4vw;
}
.order-card-wrap {
  display: flex;
}
.order-card-wrap > img {
  height: 8.533333vw;
  border-radius: 0.533333vw;
  border: 1px solid #eee;
  width: 8.533333vw;
  margin-right: 2.666667vw;
}
.order-card-content {
  flex: 1;
}
.order-card-head {
  border-bottom: 1px solid #eee;
  padding-right: 3.466667vw;
  padding-bottom: 2.666667vw;
}
.order-card-head .title {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.order-card-head .title > a {
  font-size: 1rem;
  line-height: 1.5em;
  color: #333;
  text-decoration: none;
  display: flex;
  align-items: center;
}
.order-card-head .title > a > span {
  display: inline-block;
  max-width: 10em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.order-card-head .title > p {
  font-size: 0.8rem;
  text-align: right;
  color: #333;
  max-width: 14em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.date-time {
  font-size: 0.6rem;
  color: #999;
}
.order-card-detail {
  display: flex;
  justify-content: space-between;
  padding: 4vw 3.466667vw 4vw 0;
  font-size: 0.8rem;
}
.order-card-detail .detail {
  color: #666;
  display: flex;
  align-items: center;
}
.order-card-detail .price {
  flex-basis: 16vw;
  text-align: right;
  color: #333;
  font-weight: 700;
}
.order-card-bottom {
  display: flex;
  border-top: 1px solid #eee;
  padding: 2.666667vw 4.266667vw;
  justify-content: flex-end;
}
.cardbutton {
  padding: 1.333333vw 2.666667vw;
  border: 1px solid #2395ff;
  border-radius: 0.533333vw;
  background-color: transparent;
  outline: none;
  font-size: 0.8rem;
  color: #2395ff;
  margin-left: 2vw;
}
</style>
