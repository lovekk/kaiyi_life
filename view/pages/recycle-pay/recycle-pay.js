

var app = getApp();
Page({
  data: {
    userinfo:[],
    now_money:0,
    now_jifen:0,
    to_name:'',
    to_id:0,
    // 是否显示
    show:[false,true],
    style:['now','']
  },
  onLoad: function (options) {
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    var that = this;

    app.setBarColor();
    app.setUserInfo();
    this.getUserInfo();
    
    // 获取传来的参数
    if (options.to_name){
      that.setData({
        to_name: options.to_name
      });
      that.to_name =options.to_name;
    }
    if (options.to_uid){
      that.setData({
        to_uid: options.to_uid
      });
      that.to_uid =options.to_uid;
    }
    // 打印对方信息
    console.log(that.to_name);
    console.log(that.to_uid);

  },


  tabFun(evt){
    let {id} = evt.currentTarget.dataset;
    // 第1步把show数组中的元素都变成true，然后再把我们的点击数组变为false
    // es6提供的
    this.data.show.forEach((item,index)=>{
      this.data.show[index] = true;
      this.data.style[index] = '';
    });
    // 第2步把点击索引修改为false
    this.data.show[id] = false;
    this.data.style[id] = 'now';

    // 第3步：发送到视图
    this.setData({
      show:this.data.show,
      style:this.data.style
    })
  },

  // 用户信息 我的信息
  getUserInfo:function(){
    var that = this;
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_user_info?uid=' + app.globalData.uid,
      method: 'GET',
      success: function (res) {
        console.log(res.data.data);
        that.setData({
          userinfo: res.data.data,
        })
        that.now_money=res.data.data.now_money;
        that.now_jifen=res.data.data.integral;
        // 打印我的余额
        console.log(that.now_money);
        console.log(that.now_jifen);
      }
    })
  },

  // 转凯易币
  submitSubM:function(e){
    var that = this;
    let money = e.detail.value.number;

    // 判断金额
    if (!money) {
      wx.showToast({
        title: '请输入金额',
        icon: 'none',
        duration: 1500,
      })
      return false;
    }
    // 判断金额
    if (that.now_money < money) {
      wx.showToast({
        title: '凯易币余额不足',
        icon: 'none',
        duration: 1500,
      })
      return false;
    }

    // 转账
    wx.request({
      //user_wechat_recharge
      url: app.globalData.url + '/routine/auth_api/move_money?uid=' + app.globalData.uid,
      method: 'POST',
      data: {
        to_uid: that.to_uid,
        to_name:that.to_name,
        price: e.detail.value.number
      },
      success: function (res) {
        console.log(res.data.data);
        if (res.data.code == 200) {
          wx.showToast({
            title: '转账凯易币成功',
            icon: 'success',
            duration: 1000,
          })
          setTimeout(function () {
            wx.navigateTo({
              // url: '/pages/main/main?now=' + that.data.now_money + '&uid=' + app.globalData.uid,
              url: '/pages/user/user',
            })
          }, 1500)
          
        }else{
          wx.showToast({
            title: '转账凯易币失败',
            icon: 'none',
            duration: 1000,
          })
        }
      }
    })
  },


    // 转凯积分
    submitSubJ:function(e){
      var that = this;
      let jifen = e.detail.value.jifen;
  
      // 判断积分
      if (!jifen) {
        wx.showToast({
          title: '请输入积分',
          icon: 'none',
          duration: 1500,
        })
        return false;
      }
      // 判断积分
      if (that.now_jifen < jifen) {
        wx.showToast({
          title: '积分余额不足',
          icon: 'none',
          duration: 1500,
        })
        return false;
      }
  
      // 转账
      wx.request({
        //user_wechat_recharge
        url: app.globalData.url + '/routine/auth_api/move_jifen?uid=' + app.globalData.uid,
        method: 'POST',
        data: {
          to_uid: that.to_uid,
          to_name:that.to_name,
          price: e.detail.value.jifen
        },
        success: function (res) {
          console.log(res.data.data);
          if (res.data.code == 200) {
            wx.showToast({
              title: '转账积分成功',
              icon: 'success',
              duration: 1000,
            })
            setTimeout(function () {
              wx.navigateTo({
                // url: '/pages/main/main?now=' + that.data.now_money + '&uid=' + app.globalData.uid,
                url: '/pages/user/user',
              })
            }, 1500)
            
          }else{
            wx.showToast({
              title: '转账积分失败',
              icon: 'none',
              duration: 1000,
            })
          }
        }
      })
  
    },

})

