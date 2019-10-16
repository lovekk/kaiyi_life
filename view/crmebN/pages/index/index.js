//index.js
//获取应用实例
var app = getApp();
var wxh = require('../../utils/wxh.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    url: app.globalData.urlImages,
    imgUrls:[],
    lovely:[],
    menus:[],
    indicatorDots: true,//是否显示面板指示点;
    autoplay: true,//是否自动播放;
    interval: 3000,//动画间隔的时间;
    duration: 500,//动画播放的时长;
    indicatorColor: "rgba(51, 51, 51, .3)",
    indicatorActivecolor: "#ffffff",
    recommendList:[],
    newList:[],
    hotList:[],
    benefitList:[],
    likeList:[],
    offset: 0,
    title: "玩命加载中...",
    hidden: false
  },
  goUrl:function(e){
    if (e.currentTarget.dataset.url != '#'){
      wx.navigateTo({
        url: e.currentTarget.dataset.url,
      })
    }
  },
  torday:function(e){
    wx.switchTab({
      url: '/pages/productSort/productSort'
    });
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.setBarColor();
    var that = this;
    if (app.globalData.config !=null){
      wx.setNavigationBarTitle({
        title: app.globalData.config.routine_name,
      })
    }else{
      wx.setNavigationBarTitle({
        title: '商城首页',
      })
    }
    
    // if (options.spid){
    //   app.globalData.spid = options.spid;
    //   console.log(options);
    // }
    app.setUserInfo();
    that.getIndexInfo();
  },
  getIndexInfo:function(){
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    var that = this;
    wx.request({
      url: app.globalData.url + '/routine/auth_api/index?uid=' + app.globalData.uid,
      method: 'POST',
      header: header,
      success: function (res) {
        that.setData({
          imgUrls: res.data.data.banner,
          recommendList: res.data.data.best,//精品
          newList: res.data.data.new,//首发新品
          hotList: res.data.data.hot,//热卖单品
          benefitList: res.data.data.benefit,//促销
          lovely: res.data.data.lovely,//猜猜你喜欢上面广告位
          menus: res.data.data.menus,//导航
          likeList: res.data.data.like//猜猜喜欢
        })
      }
    })
  },
  onReachBottom: function (p) {
    var that = this;
    var limit = 20;
    var offset = that.data.offset;
    if (!offset) offset = 1;
    var startpage = limit * offset;
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_hot_product?uid=' + app.globalData.uid,
      data: { limit: limit, offset: startpage },
      method: 'POST',
      header: header,
      success: function (res) {
        var len = res.data.data.length;
        for (var i = 0; i < len; i++) {
          that.data.likeList.push(res.data.data[i])
        }
        that.setData({
          offset: offset + 1,
          likeList: that.data.likeList
        });
        if (len < limit) {
          that.setData({
            title: "数据已经加载完成",
            hidden: true
          });
          return false;
        }
      },
      fail: function (res) {
        console.log('submit fail');
      },
      complete: function (res) {
        console.log('submit complete');
      }
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
    
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this;
    return {
      title: app.globalData.config.routine_name,
      path: '/pages/index/index?scene=' + app.globalData.uid,
      // imageUrl: that.data.url + that.data.product.image,
      success: function () {
        wx.showToast({
          title: '分享成功',
          icon: 'success',
          duration: 2000
        })
      }
    }
  }
})