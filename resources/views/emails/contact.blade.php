<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
</head>
<body style="background:#f7f7f7;">
<div style="margin:20px 20px 0px;background:#fff;border-radius: 4px 4px 0px 0px;">
    <h3>Contact Form Submission</h3>
</div>
<div style="margin:0px 20px 0px;background:#fff;border-top:1px solid #f7f7f7;border-bottom:1px solid #f7f7f7;padding: 40px 80px;">
    <dl>
        <dt>Name</dt>
        <dd>{{ $data['name'] }}</dd>
        <dt>Organization</dt>
        <dd>{{ $data['organization'] }}</dd>
        <dt>From</dt>
        <dd>{{ $data['from'] }}</dd>
        <dt>Phone</dt>
        <dd>{{ $data['phone_one'] }}</dd>
        <dt>Comments</dt>
        <dd>{{ $data['comments'] }}</dd>
    </dl>
</div>
</body>
</html>