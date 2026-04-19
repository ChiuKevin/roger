<html>
<head>
    <title>綠界科技 ECPay - 信用卡號輸入頁面</title>
</head>
<body>
<div style="padding: 4rem;">
    <div style="display: grid; align-items: center; justify-content: center;">
        <ul>
            <li>
                <!--設定綁訂信用卡畫面的語言-->
                <div style="margin-bottom: 1rem;">
                    <label for="language" style="margin-right: 2px;">設定綁訂信用卡畫面的語言：</label>
                    <select id="language">
                        <option value="zh-TW">繁體中文</option>
                        <option value="en-US">English</option>
                    </select>
                </div>
            </li>
            <li>
                <!--取得綁定信用卡代碼 BindCardPayToken 並建立綁定信用卡交易-->
                <div style="margin-bottom: 1rem;">
                    <div>
                        <label style="margin-right: 2px;">輸入信用卡資訊後，點擊提交：</label>
                        <input id="btnBindCardPayToken" type="button" class="btn single btn-gray-dark" value="提交"/>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <!--錯誤提示訊息-->
    <div id="err"></div>

    <!--渲染站內付 2.0 的綁訂信用卡畫面，請勿更動 id-->
    <div id="ECPayPayment"></div>
</div>
</body>
</html>
<!-- 綠界科技 ECPay SDK 需引用 JS 區塊 -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/node-forge@0.7.0/dist/forge.min.js"></script>
<!-- 引用綠界 JS 元件 測試環境 -->
<script src="https://ecpg-stage.ecpay.com.tw/Scripts/sdk-1.0.0.js?t=20210121100116"></script>
<!-- 引用綠界 JS 元件 正式環境 -->
<!-- <script src="https://ecpg.ecpay.com.tw/Scripts/sdk-1.0.0.js?t=20210121100116"></script> -->

<script type="text/javascript">
    var environment = 'STAGE'; // 請設定要連線的環境: 測試 STAGE ,正式 PROD
    var envi = getEnvi(environment);
    var _token = ''; // 廠商驗證碼 Token
    var _authorization = '';// Bearer Token
    var _bindCardPayToken = ''; // 綁定信用卡代碼
    var url = window.location.protocol + '//' + window.location.host;

    // 模擬流程:
    // (1)透過查詢字串取得 廠商驗證碼 Token 及 Bearer Token
    // (2)取得綁定信用卡代碼 BindCardPayToken 並建立綁定信用卡交易
    $(function () {
        getInitToken()
    })

    // (1)透過查詢字串取得 廠商驗證碼 Token 及 Bearer Token
    function getInitToken() {
        _token = "{{ request('token') }}";
        _authorization = "{{ request('authorization') }}";
        initECpaySDK();
    }

    // 初始化 SDK
    function initECpaySDK() {
        ECPay.initialize(envi, 1, function (errMsg) {
            try {
                addBindingCard();
            } catch (err) {
                errHandle(err);
            }
        })
    }

    // 取得綁定信用卡畫面
    function addBindingCard() {
        ECPay.addBindingCard(_token, $('#language').val(), function (errMsg) {
            if (errMsg != null)
                errHandle(errMsg);
        });
    }

    // 切換 SDK 語系
    $('#language').on('change', function () {
        try {
            if (ECPay !== undefined && _token !== '') {
                addBindingCard();
            }
        } catch (err) {
            errHandle(err);
        }
    });

    // (2)取得綁定信用卡代碼 BindCardPayToken 並建立綁定信用卡交易
    $('#btnBindCardPayToken').on('click', function () {
        try {
            if (ECPay !== undefined && _token !== '') {
                ECPay.getBindCardPayToken(function (bindCardPayToken, errMsg) {
                    if (errMsg != null) {
                        errHandle(errMsg);
                        return
                    }

                    _bindCardPayToken = bindCardPayToken.BindCardPayToken

                    $('#err').empty();

                    let locale = $('#language').val();

                    if (locale === 'zh-TW') {
                        locale = 'zh_tw';
                    } else if (locale === 'en-US') {
                        locale = 'en';
                    }

                    $.ajax({
                        url: url + '/consumer/user/cards/bindCard',
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + _authorization,
                            'region': 'tw',
                            'locale': locale
                        },
                        data: {
                            BindCardPayToken: _bindCardPayToken
                        },
                        beforeSend: function () {
                            processHandle('連線處理中，請稍後...');
                        },
                        complete: function (xhr, status) {
                            var response = xhr.responseJSON;
                            if (response) {
                                if (status === 'success') {
                                    successHandle(response.data);
                                } else {
                                    errHandle(response.message);
                                }
                            } else {
                                alert('An unexpected error occurred.');
                            }
                        },
                        dataType: 'json'
                    });
                });
            } else {
                errHandle('請先產生初始廠商驗證碼 Token 後再取得 BindCardPayToken');
            }
        } catch (err) {
            errHandle(err);
        }
    });

    // 取得環境參數
    function getEnvi(env) {
        var result = 'STAGE';
        switch (env) {
            case 'STAGE':
                result = 'Stage';
                break;
            case 'PROD':
                result = 'Prod';
                break;
        }
        return result;
    }

    // 進行中訊息處理
    function processHandle(str) {
        $('#err').empty();
        if (str != null) {
            $('#err').append('<div style="text-align: center;"><label style="color: blue;">' + str + '</label></div>');
        } else {
            $('#err').append('<div style="text-align: center;"><label style="color: blue;">處理中</label></div>');
        }
    }

    // 成功訊息處理
    function successHandle(str) {
        $('#err').empty();
        if (str != null) {
            $('#err').append('<div style="text-align: center;"><label style="color: green;">' + str + '</label></div>');
        } else {
            $('#err').append('<div style="text-align: center;"><label style="color: green;">成功</label></div>');
        }
    }

    // 錯誤訊息處理
    function errHandle(strErr) {
        $('#err').empty();
        if (strErr != null) {
            $('#err').append('<div style="text-align: center;"><label style="color: red;">' + strErr + '</label></div>');
        } else {
            $('#err').append('<div style="text-align: center;"><label style="color: red;">Token取得失敗</label></div>');
        }
    }
</script>