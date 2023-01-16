<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body style="">
    <div style="background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);min-height:723px; ">
        <div style="text-align: center; padding-top:100px" >
            
            <p style="font-size: 12px; color:#DDCCAA; line-height: 18px;font-family: sans-serif;">MOVIE QUOTES</p>
        </div>
        <div style="text-align:start; margin-top:50px; margin-left: 5%;">
            <p style="font-family: sans-serif;color:white;font-family: sans-serif; font-size: 16px">Hola!</p>
            <p style="font-family: sans-serif; sans-serif; font-size: 16px; color:white">You just added a new email, please verify it:</p> 
            <a 
                
                href="{{env('FRONT_URL').'verify-new-email?token='.$email->token}}"
                >
                <button style="cursor: pointer; font-family: sans-serif; margin-top: 10px;  text-align:center; background-color:#E31221; border-radius:8px; border:none; color:white;padding: 15px; height:56px; font-size: 16px; text-decoration:none;" >Verify Email</button> 
            </a> 

            <p style="color:white; font-size:16px; font-family:sans-serif; margin-top:30px;">If clicking doesn't work, you can try copying and pasting it to your browser:</p>
            <a style="display:inline; width: 100%; white-space:normal" href="{{env('FRONT_URL').'verify-new-email?token='.$email->token}}" > <p style="white-space:unset; color: #DDCCAA ; text-decoration: none; text-align:left;  padding-top:20px">{{env('FRONT_URL').'verify-new-email?token='.$email->token}}</p> </a>
            <p style="color:white; font-size:16px; font-family:sans-serif">If you have any problems, please contact us: support@moviequotes.ge</p>
            <p style="color:white; font-size:16px; font-family:sans-serif">MovieQuotes Crew</p>
        </div>
     </div>
</body>
</html>
