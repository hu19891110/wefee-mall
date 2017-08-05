document.write('<script src="/addons/assets/wefeemall/static/js/layer_mobile/layer.js"></script>');

var loading = function (callback) {
	layer.open({
		type: 2,
		shadeClose: false,
	});
	callback();
}

var _alert = function (msg) {
	layer.open({
		content: msg
		,skin: 'msg'
		,time: 2
	});
}

var confirm = function (msg, success) {
	layer.open({
		content: msg
		,btn: ['确定', '取消']
		,yes: function(index){
			success();
			layer.close(index);
		}
	});
}

$(function () {

	$('.show-menu').click(function () {
		$('.menu').toggle(500);
	});

	/** 更换验证码 */
	$('.verify-code').click(function () {
		var src = $(this).attr('src');
		$(this).attr('src', src + '?' + Math.random());
	});

	/** 获取手机验证码 */
	$('.mobile-verify-code').click(function () {
		if ($('input[name="verify_code"]').val() == '' || $('input[name="username"]').val() == '') {
			_alert('请输入必要信息');
			return ;
		}

		var interval = setInterval(function () {
			$('.mobile-verify-code').addClass('weui-btn_disabled');
			var text = $('.mobile-verify-code').text();
			if (text == '获取验证码') {
				$('.mobile-verify-code').text('60s');
			} else {
				$('.mobile-verify-code').text((parseInt(text) - 1) + 's');
			}
		}, 1000);

		setTimeout(function () {
			$('.mobile-verify-code').removeClass('weui-btn_disabled').text('获取验证码');
			clearInterval(interval);
		}, 6000);

		$.post($(this).attr('data-url') + '?' + Math.random(), {
			verify_code: $('input[name="verify_code"]').val(),
			mobile: $('input[name="username"]').val(),
			type: $(this).attr('data-type')
		}, function (data) {
			_alert(data.msg);
		}, 'json');
	});

	/** 删除 */
	$('.delete').click(function () {
		var url = $(this).attr('data-url'),id=$(this).attr('data-id');
		confirm('确定要删除？', function () {
			$.post(url, {id: id}, function (data) {
				_alert(data.msg);
				location.reload();
			}, 'json');
		});
	});

});