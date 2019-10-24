// pages/mall/payment/payment.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userinfo:[],
    now_money:0,
    to_name:'',
    to_id:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    app.setBarColor();
    app.setUserInfo();
    this.getUserInfo();
    
    if (options.to_name){
      that.setData({
        to_name: options.to_name
      });
    }
    if (options.to_uid){
      that.setData({
        to_uid: options.to_uid
      });
    }
  },
  getUserInfo:function(){
    var that = this;
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_user_info?uid=' + app.globalData.uid,
      method: 'GET',
      success: function (res) {
        that.setData({
          userinfo: res.data.data,
          now_money: res.data.data.now_money
        })
      }
    })
  },

  submitSub:function(e){
    var that = this;
    // 发送信息模板
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_form_id?uid=' + app.globalData.uid,
      method: 'GET',
      data: {
        formId: e.detail.formId
      },
      success: function (res) { }
    })

    // 判断金额
    if (!e.detail.value.number) {
      wx.showToast({
        title: '请输入金额',
        icon: 'none',
        duration: 1000,
      })
      return false;
    }

    // 转账
    wx.request({
      //user_wechat_recharge
      url: app.globalData.url + '/routine/auth_api/user_wechat_recharge?uid=' + app.globalData.uid,
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
            title: '转账成功',
            icon: 'success',
            duration: 1000,
          })
          setTimeout(function () {
            wx.navigateTo({
              url: '/pages/main/main?now=' + that.data.now_money + '&uid=' + app.globalData.uid,
            })
          }, 1500)
          
        }else{
          wx.showToast({
            title: '转账失败',
            icon: 'none',
            duration: 1000,
          })
        }
      }
    })

  },

})