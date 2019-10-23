// pages/promotion-card/promotion-card.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    url: app.globalData.url,
    code:'',
    userinfo: [],
    routine_name: ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.setBarColor();
    app.setUserInfo();
    var that = this;
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    that.setData({
      routine_name:app.globalData.config.routine_name
    })
    wx.request({
      url: app.globalData.url + '/routine/auth_api/my?uid=' + app.globalData.uid,
      method: 'POST',
      header: header,
      success: function (res) {
        if (res.data.code == 200) {
          that.setData({
            userinfo: res.data.data
          })
          console.log(res.data.data);
        } else {
          that.setData({
            userinfo: []
          })
        }
      }
    });  
    that.getCode();
  },

  // 扫码
  getScancode: function() {
    console.log(111)
    // 只允许从相机扫码
      wx.scanCode({
        onlyFromCamera: true,
        success (res) {
          console.log(res.result)
        }
      })
  },


  // 获取二维码
  getCode:function(){
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    var that = this;
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_routine_code?uid=' + app.globalData.uid,
      method: 'get',
      header: header,
      success: function (res) {
        if (res.data.code == 200) {
          that.setData({
            code: res.data.msg
          })
        } else {
          wx.showToast({
            title: res.data.msg,
            icon: 'none',
            duration: 0
          })
          that.setData({
            code: ''
          })
        }
      }
    })
  },

})