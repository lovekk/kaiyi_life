var app = getApp();
Page({
  data: {
    userAddress:[],
    date:'',
    time:'',
    array: ['上午(09:00-12:00)', '中午(12:00-14:00)', '下午(14:00-18:00)', '晚上(18:00-21:00)'],
    index:2,
    flag:0,
    id:0,
    app_date:'',
  },

  onLoad: function (options) {
    this.getUserAddress();

  },

  // 选择器
  bindPickerChange: function(e) {
    // console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index: e.detail.value
    })
  },
  bindDateChange: function(e) {
    // console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      date: e.detail.value
    })
  },
  bindTimeChange: function(e) {
    // console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      time: e.detail.value
    })
  },


  // 获得用户默认地址
  getUserAddress: function () {//get_user_address
    var that = this;
    wx.request({
      url: app.globalData.url + '/routine/auth_api/user_default_address?uid=' + app.globalData.uid,
      method: 'GET',
      data: {
        addressId : that.data.id
      },
      success: function (res) {
        console.log(res);
        that.setData({
          userAddress: res.data.data,
        })
      }
    })
  },
  
  
  recycleSubmit: function (e) {
    var warn = "";
    var that = this;
    var flag = true;
    var name = e.detail.value.name;
    var phone = e.detail.value.phone;
    var area = e.detail.value.area;
    var fulladdress = e.detail.value.fulladdress;
    var goods = e.detail.value.goods;
    var apptime = e.detail.value.apptime;
    var appdate = e.detail.value.appdate;
    var remark = e.detail.value.remark;
    if (name == "") {
      warn = '请输入姓名';
    } else if (!/^1(3|4|5|7|8|9)\d{9}$/i.test(phone)) {
      warn = '您输入的手机号有误'
    } else if (area == '["省","市","区"]'){
      warn = '请选择地区';
    } else if (fulladdress == "") {
      warn = "请填写具体地址";
    } else if (apptime == "") {
      warn = "请填写上门时间";
    }else if (appdate == "") {
      warn = "请填写上门日期";
    }else{
      flag = false;
    }
    if(flag==true){
      wx.showModal({
        title: '提示',
        content: warn
      })
    }else{
     
      wx.request({
        url: app.globalData.url + '/routine/auth_api/add_recycle?uid=' + app.globalData.uid,
        method: 'POST',
        data: {
          name : name,
          phone : phone,
          area : area,
          fulladdress : fulladdress,
          goods : goods,
          apptime : apptime,
          appdate : appdate,
          remark : remark
        },
        success: function (res) {
          if (res.data.code == 200) {
            that.app_date = res.data.data.appdate;
            console
            that.setData({
              app_date: that.app_date,
            })
            wx.showToast({
              title: '预约成功！',
              icon: 'success',
              duration: 2000
            })

            setTimeout(function () {
              //返回上一级关闭当前页面
              wx.navigateBack({
                delta: 1
              })
            },2200)

            // setTimeout(function () {
            //   wx.navigateTo({ //跳转至指定页面并关闭其他打开的所有页面（这个最好用在返回至首页的的时候）
            //     url: '/pages/address/address'
            //   })
            // },1200)
          }
        }
      })
    }
  }


})