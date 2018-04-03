<html>
<style type="text/css">
	

body,#console{
	    background: gray;
    color: #fff;
    font-size: 7px;
}
#console{
	position: relative;
	top:50px;
	bottom: 50px;
	overflow-y: scroll;
	height: 85%;
}
#headinfo{
position:absolute;
top:0px;
right:16px;
width:100%;
height:40px;
text-align:center;
background:#ccc;
z-index:2;
overflow:hidden; 
line-height: 40px;
color: black;
font-weight: bold;
}
#buttominfo{
position:absolute;
bottom:0px;
right:16px;
width:100%;
height:40px;
text-align:center;
background:#ccc;
z-index:3;
overflow:hidden; 
line-height: 40px;
color: black;
font-weight: bold;
}
p{
line-height:6px;
}
</style>
<body style="background: gray">
	<div id="headinfo"></div>
	<div id="console">程序启动中...<br/></div>
	<div id="buttominfo"></div>

</body>
</html>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>  

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.3/jquery.min.js"></script>
<script>
	function setRandomTime(n) {
    return parseInt(Math.random() * n * 1000)
	};
	function autoArticle(a_start,a_end){

	    var p = 60 * 60 * 24 * 1000 * 5;
	    var q = 1522052538194 + p;
	    var r = 0;
	    var s = 0;
	    var t = 0;
	    var u = 0;	

		function getBoardAndHotArticle(randomIntervalTime){
				setTimeout(function(){
					var url="https://be03.bihu.com/bihube-pc/api/content/queryBoardList";
					$.ajax({
								url: url,
								type: 'post',
								dataType: 'json',
								async: false,
								cache: true,  
								data: null,
								success: function(a) {
									$("#console").append("<p style=\"color:white\">"+url+"</p>");   							 
									var b = a.data;
									var c = a.res;
									var d = a.resMsg;
									url="https://be03.bihu.com/bihube-pc/api/content/show/hotArtList";
									$.ajax({
										url: url,
										type: 'post',
										dataType: 'json',
										async: false,
										cache: true,  
										data: null,
										success: function(a) {							 
											var b = a.data;
											var c = a.res;
											var d = a.resMsg;
											$("#console").append("<p style=\"color:white\">"+url+"</p>"); 						
										},
										error: function(data){  
									            $("#console").append("<p style=\"color:orange\">"+url+" 请求出错! 再次请求间隔时间大约："+randomIntervalTime/10000 +" s"+"</p>");  
									        }  
									});				
								},
								error: function(data){  
							            $("#console").append("<p style=\"color:orange\">"+url+" 请求出错! 再次请求间隔时间大约："+randomIntervalTime/10000 +" s"+"</p>"); 
							        }  
							});
				},2000);
		}
		a_currentIndex = a_start;
 		a_locknum = 0;
 		a_sumtime= 0;
		function getArticle() {
					if(a_currentIndex>=a_end){   
				        return;  
				    } 
				    a_randomIntervalTime =2000 + setRandomTime(5);
					setTimeout(function(){		
					$.ajax({
						url: "https://be03.bihu.com/bihube-pc/api/content/show/getArticle",
						type: 'post',
						dataType: 'json',
						async: false,
						cache: true,  
						data: {
							'userId':'',
							'accessToken':'',
							'artId': a_currentIndex
						},
						success: function(a) {							 
							var b = a.data;
							var c = a.res;
							var d = a.resMsg;
							if (c == 1) {
								if(b!=null){
									insertArticle(b);
									$("#console").append("<p style=\"color:white\">"+JSON.stringify(b.title)+"</p>"); 
									$("#console").append("<p style=\"color:yellow\">"+d+" article "+a_currentIndex+" "+c+" 获取下次数据间隔时间大约："+a_randomIntervalTime/10000 +" s"+"</p>"); 			
								}
								else{
									$("#console").append("<p style=\"color:orange\">"+a_currentIndex+" is no article!"+" 获取下次数据间隔时间大约："+a_randomIntervalTime/10000 +" s"+"</p>"); 			
								}
								a_currentIndex++; 
								a_locknum=0;							
							} 
							else if(c==100004){
								a_currentIndex++; 
							}
							else {
								a_randomIntervalTime=65000 + setRandomTime(30);
								// a_randomIntervalTime=2000 + setRandomTime(10);
								a_sumtime=a_sumtime+a_randomIntervalTime/10000;
								// if(p_locknum>50&&p_locknum%100==0) {
								// 	console.clear();
								// }
								$("#console").append("<p style=\"color:orange\">"+d+" article "+a_currentIndex+" 重试"+(++a_locknum)+"次 "+c+" 间隔时间大约："+a_randomIntervalTime/10000 +" s,总耗时："+a_sumtime+" s </p>"); 
								setTimeout(() => {
									getBoardAndHotArticle(a_randomIntervalTime);
									getArticle();
								}, 120000);
							}
							getBoardAndHotArticle(a_randomIntervalTime);
							getArticle();

						},
						error: function(data){  
					            $("#console").append("<p style=\"color:red\">"+data+" user "+a_currentIndex+" 请求出错! 再次请求间隔时间大约："+a_randomIntervalTime/10000 +" s"+"</p>");  
					            getBoardAndHotArticle(a_randomIntervalTime);
					            getArticle();  
					    },
					    always:function(data){
					    	getBoardAndHotArticle(a_randomIntervalTime);
					    }  
					});
				}, a_randomIntervalTime)
		} ;	

		
	
		function insertArticle(article){
				$.ajax({
					url: "http://localhost:8081/bihuss/AutoSoluArticle.php",
					type: 'post',
					dataType: 'json',
					data: {
						"code":article.codeName,
						"artId":article.id,
						"userId": article.userId,
						"userName":article.userName,
						"title":article.title,
						"cmts": article.cmts,
        				"creatime":article.creatime,
        				"content":article.content,	
						"snapContent":article.snapContent,
						"money":article.money,
						"ups":article.ups,
						"downs":article.downs

					    
						},
					success: function(a) {
						var b = a.status;
						var c = a.delete;
						var d = a.add;
						var e= a.error;
						if (b == 200) {
							$("#console").append("<p style=\"color:green\">"+article.id+":"+article.title+"====已经被本地服务器收录."+"</p>"); 
						} else {
							$("#console").append("<p style=\"color:red\">"+article.id+":"+article.title+"====本地服务器收录错误. "+e+"</p>"); 
							setTimeout(() => {
								insertArticle(article);
							}, 120000);
						}
					}
				});		
		};

		getArticle();

	}
	var start_index=(function() {
		var newest_id=0;
					$.ajax({
						url: "http://localhost:8081/bihuss/getDbNew.php",
						type: 'post',
						dataType: 'json',
						async : false,
						data: {
							"type":"article"				    
							},
						success: function(a) {
							var b = a.status;
							var c = a.newid["max(artid)"];
							var e= a.error;
							if (b == 200) {
								$("#console").append("当前最新记录为:"+c+"</br>"); 
								newest_id=c;
							} else {
								$("#console").append("本地服务器获取最新记录错误. "+e+"</br>");  
								setTimeout(() => {
									getArticleDbnew();
								}, 120000);
							}
						}
					});
		return newest_id;	
	})();

	var newest_index=(function(){
					var newest_id=0;
					$.ajax({
						url: "https://be03.bihu.com/bihube-pc/api/content/show/newestArtList",
						type: 'post',
						dataType: 'json',
						async : false,
						data: {"pageNum":1},
						success: function(res) {
							var b = res.data.list[0].id;
							var c = res.res;
							var d= res.resMsg;
							if (c == 1) {
								$("#console").append("当前壁虎最新记录为:"+b+"</br>");  
								newest_id=b;
								
							} else {
								$("#console").append("壁虎服务器获取最新记录错误. "+e+"</br>");   
								setTimeout(() => {
									getBihuArticleNew();
								}, 120000);
							}
						}
					});
					return newest_id;
	})();

	window.onload = function() {
        // alert(decodeURIComponent('程序完美运行，脚本已经启动，稍后会给出数据'));
		$("#headinfo").text("当前最新记录为:"+start_index); 
		$("#buttominfo").text("当前壁虎最新记录为:"+newest_index); 
		autoArticle(parseInt(start_index),parseInt(newest_index));
		h = setInterval(() => {
			window.location.reload();

		},1800000 + setRandomTime(20));

			
	};




</script>