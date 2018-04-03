
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
<body style="background: #gray">
	<div id="headinfo"></div>
	<div id="console">程序启动中...<br/></div>
	<div id="buttominfo"></div>

</body>
</html>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>  

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.3/jquery.min.js"></script>
<script>

	function setRandomTime(n) {
    return parseInt(Math.random() * n * 1000);
	}

	function autoPeople(p_start,p_end){

		
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
							            $("#console").append("<p style=\"color:red\">"+url+" 请求出错! 再次请求间隔时间大约："+randomIntervalTime/10000 +" s"+"</p>");  
							        }  
							});
				},2000);
		}
	
 		p_currentIndex = p_start;
 		p_locknum = 0;
 		p_sumtime= 0;
		function getPeople() {
					if(p_currentIndex>=p_end){   
				        return;  
				    } 
				    p_randomIntervalTime =2000 + setRandomTime(5);
					setTimeout(function(){		
					$.ajax({
						url: "https://be03.bihu.com/bihube-pc/api/content/show/userHomePage",
						type: 'post',
						dataType: 'json',
						async: false,
						cache: true,  
						data: {
							'queryUserId': p_currentIndex
						},
						success: function(a) {							 
							var b = a.data;
							var c = a.res;
							var d = a.resMsg;
							if (c == 1) {
								if(b!=null){
									insertPeople(b);
									$("#console").append("<p style=\"color:bule\">"+JSON.stringify(b)+"</p>"); 
									$("#console").append("<p style=\"color:yellow\">"+d+" user "+p_currentIndex+" "+c+" 获取下次数据间隔时间大约："+p_randomIntervalTime/10000 +" s"+"</p>"); 		
								}
								else{
									$("#console").append("<p style=\"color:orange\">"+p_currentIndex+" is no user!"+" 获取下次数据间隔时间大约："+p_randomIntervalTime/10000 +" s"+"</p>"); 			
								}
								p_currentIndex++; 
								p_locknum=0;							
							} 
							else {
								p_randomIntervalTime=65000 + setRandomTime(30);
								// p_randomIntervalTime=5000 + setRandomTime(10);
								p_sumtime=p_sumtime+p_randomIntervalTime/10000;
								// if(p_locknum>50&&p_locknum%100==0) {
								// 	console.clear();
								// }
								$("#console").append("<p style=\"color:orange\">"+d+" user "+p_currentIndex+" 重试"+(++p_locknum)+"次 "+c+" 间隔时间大约："+p_randomIntervalTime/10000 +" s,总耗时："+p_sumtime+" s"+"</p>"); 
								setTimeout(() => {
									getBoardAndHotArticle(p_randomIntervalTime);
									getPeople();
								}, 120000);
							}
							getBoardAndHotArticle(p_randomIntervalTime);							
							getPeople();

						},
						error: function(data){  
					            $("#console").append("<p style=\"color:red\">"+data+" user "+currentIndex+" 请求出错! 再次请求间隔时间大约："+p_randomIntervalTime/10000 +" s"+"</p>"); 
					            getBoardAndHotArticle(p_randomIntervalTime);
					            getPeople();  
					    },
					    always:function(data){
					    	getBoardAndHotArticle(p_randomIntervalTime);
					    } 
					});
				}, p_randomIntervalTime)
		} ;
		

		
		function insertPeople(people){
				$.ajax({
					url: "http://localhost:8081/bihuss/AutoSoluPeople.php",
					type: 'post',
					dataType: 'json',
					data: {
					    "userId": people.userId,
						"userName":people.userName,
						"userIcon": people.userIcon,
						"artNum":people.artNum,
					    "fans": people.fans,
					    "follow":people.follow,
					    "follows": people.follows,
					    "info": people.info
						},
					success: function(a) {
						var b = a.status;
						var c = a.delete;
						var d = a.add;
						var e= a.error;
						if (b == 200) {
							$("#console").append("<p style=\"color:green\">"+people.userId+":"+people.userName+"已经被本地服务器收录."+"</p>"); 
						} else {
							$("#console").append("<p style=\"color:red\">"+people.userId+":"+people.userName+"本地服务器收录错误. "+e+"</p>"); 
							setTimeout(() => {
								insertPeople(people);
							}, 120000);
						}
					}
				});		
		};

		getPeople();


	}
	var start_index=(function() {
		var newest_id=0;
					$.ajax({
						url: "http://localhost:8081/bihuss/getDbNew.php",
						type: 'post',
						dataType: 'json',
						async : false,
						data: {
							"type":"people"				    
							},
						success: function(a) {
							var b = a.status;
							var c = a.newid["max(bh_id)"];
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

	var newest_index=99999999;

	window.onload = function() {
        // alert(decodeURIComponent('程序完美运行，脚本已经启动，稍后会给出数据'));
		$("#headinfo").text("当前搜录会员最新记录为:"+start_index); 
		$("#buttominfo").text("当前会员壁虎最新记录为:"+newest_index); 
		autoPeople(parseInt(start_index),parseInt(newest_index));
		h = setInterval(() => {
			window.location.reload();

		},1800000 + setRandomTime(20));

			
	};


</script>
