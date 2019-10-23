var app = getApp();
// pages/main/main.js
Page({
  data: {
    url: app.globalData.urlImages,
    now_money:'',
    mainArray:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.setBarColor();
    var that = this;
    var now = options.now;
    that.setData({
      now_money: now
    });
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_recycle_order?uid=' + app.globalData.uid,
      method: 'POST',
      success: function (res) {
        that.setData({
          mainArray: res.data.data
        })
        console
      }
    })
  },

})