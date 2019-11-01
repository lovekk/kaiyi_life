

var app = getApp();
Page({
  data: {
    schoolinfo: [],
    homeinfo: [],
    // 是否显示
    show:[false,true],
    style:['now','']
  },
  onLoad: function (options) {
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    var that = this;
    // 学校
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_school?uid=' + app.globalData.uid,
      method: 'POST',
      header: header,
      success: function (res) {
        // console.log(res);
        if (res.data.code == 200) {
        that.setData({
          schoolinfo: res.data.data
        })
        }else{
          that.setData({
            extractsum: ''
          })
        }
      },
      fail: function (res) {
        console.log('school submit fail');
      },
      complete: function (res) {
        console.log('school submit complete');
      }
    })
    // 小区
    wx.request({
      url: app.globalData.url + '/routine/auth_api/get_home?uid=' + app.globalData.uid,
      method: 'POST',
      header: header,
      success: function (res) {
        // console.log(res);
        if (res.data.code == 200) {
        that.setData({
          homeinfo: res.data.data
        })
        }else{
          that.setData({
            extractsum: ''
          })
        }
      },
      fail: function (res) {
        console.log('home submit fail');
      },
      complete: function (res) {
        console.log('home submit complete');
      }
    })
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

  // 选择学校
  chooseOne(evt){
    console.log(evt.currentTarget.dataset.name);
    var header = {
      'content-type': 'application/x-www-form-urlencoded',
    };
    var that = this;
    // 学校
    wx.request({
      url: app.globalData.url + '/routine/auth_api/choose_school?uid=' + app.globalData.uid,
      method: 'POST',
      data: { 
        school_name: evt.currentTarget.dataset.name
      },
      header: header,
      success: function (res) {
        // console.log(res);
        if (res.data.code == 200) {
          //关闭所有页面，打开到应用内的某个页面
          wx.reLaunch({
            url: '/pages/index/index'
          })
        }else{
          console.log('school submit fail1');
        }
      },
      fail: function (res) {
        console.log('school submit fail2');
      },
      complete: function (res) {
        console.log('school submit complete');
      }
    })
  },
})