<style type="text/css">
*, .dropdown {font-family: 'Roboto Condensed', sans-serif}

html, body {
  background-color: #eee;
  background-image: url("<?=base_url('images/login.jpg');?>");
  height: 100%;
  margin:0;
  padding-top: 2%;
}

#login-box {
  background-color: rgba(255,255,255,.7);
  width: 350px;
  margin: 0 auto 5% auto;
  padding: 15px
}

.field {
  position: relative !important;
  margin-bottom: 20px;
}

input.error { border: 1px solid #ECBFBF !important;}

div.error {
  display: none;
  border-radius: 4px;
  margin: -5px 10px 10px 10px;
  padding: 5px;
  border: 1px dashed rgba(170, 64, 64, 0.3);
}

label.error {
  color: #992C2C;
  display: block;
  margin-left: 5px;
  width: auto;
  font-size: 12px; margin-bottom: 4px
}
label.error:last-child { margin-bottom: 0}

select:required:invalid {
  color: gray;
}
option[value=""][disabled] {
  display: none;
}
option {
  color: black; padding: 10px
}

</style>
