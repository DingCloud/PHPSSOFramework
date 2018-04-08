// SSO JavaScript Logic

var RegisterServlet = '/index.php?action=api&mod=register';

function doRegisterRequest() {
    if (document.getElementById('username').value == '' || document.getElementById('password').value == '' || document.getElementById('password2').value == '' || document.getElementById('verifycode').value == '') {
        alert('用户注册表单不能有任意一项为空，请核实后重试。');
        return false;
    }
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var password2 = document.getElementById('password2').value;
    var vcode = document.getElementById('verifycode').value;
    if (password != password2) {
        alert('二次密码确认不一致，请检查表单并修正这些问题再次尝试。');
        return false;
    }
    send_ajax({
        url: RegisterServlet,
        method: 'POST',
        data: {
            'username': username,
            'password2': password2,
            'verifycode': vcode
        },
        dataType: 'json',
        async: true,
        success: function (res) {
            refreshCode();
            refreshForm();
            if (res.code == 200) {
                alert('注册成功！请在登录页面完成登录。');
                location.href = './index.php?action=login';
            }
            else if (res.code == 402) {
                alert('验证码输入有误，请重试。');
            }
            else if (res.code == 500) {
                alert('注册失败，您键入的用户名可能已经存在或被禁止注册。');
            }
            else {
                alert('系统异常，请刷新页面后再次尝试。如多次出现，请联系管理员！');
            }
        }
    });
}

function refreshForm() {
    document.getElementById('password').value = '';
    document.getElementById('password2').value = '';
    document.getElementById('verifycode').value = '';
}
