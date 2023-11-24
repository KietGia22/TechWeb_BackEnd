<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
     <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" >
</head>
    <body style = "margin:0;
                    padding:0;
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color:darkblue">
        <div>
            <h1 style="text-align:center;
                        margin-top: 50px;
                        color:darkblue;
                    font-size:24px">Mật khẩu mới</h1>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Chào <strong>{{$name}}</strong></p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Chúng tôi xin gửi bạn một mật khẩu mới dựa theo yêu cầu đổi mật khẩu của bạn</p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Vui lòng không chia sẻ mật khẩu này với ai, kể cả người yêu của bạn để tính bảo mật của chúng tôi là 10/10 :3</p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Mật khẩu mới của bạn là: </p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:30px"><strong>{{$newPassword}}</strong></p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Hãy sử dụng mật khẩu này để đăng nhập và trải nghiệm các dịch vụ tuyệt vời của chúng tôi</p>
            <p style="text-align:center;
                        margin-top: 20px;
                        color:darkblue;
                        font-size:15px">Đừng hỏi tại sao chung tôi không để khách hàng tự tạo mật khẩu mới, vì đơn giản chúng tôi lười</p>
        </div>
        <a href='https://github.com/KietGia22' class='btn' style="background-color: #3b82f6;
                                                                    color: white !important;
                                                                    padding: 10px 20px;
                                                                    border: 2px solid;
                                                                    cursor: pointer;
                                                                    width: 100%;
                                                                    margin-top: 30px;
                                                                    border-radius: 5px;
                                                                    text-decoration: none;">BẤM VÀO ĐÊ</a>
</body>
</html>

