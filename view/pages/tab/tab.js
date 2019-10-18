Page({

  data: {
    // 是否显示
    show:[false,true],
    style:['now','']
  },
  onLoad (options) {

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
    


  }


  
})