# CI-Captcha-library
Simple MathCaptcha library for Codegniter

## Instructions, how to use it

> copy Captcha.php to Codegniter Library folder `application/libraries`
> Load this Library into controller like `$this->load->library('captcha')`

> Now, put this line of code where you want to print captcha (inside views)
`$this->captcha->output();`
    
### To varify Captcha
```
$captcha = $this->input->post('captcha');

if ($this->captcha->verify($captcha) == false) {
    $this->session->set_flashdata('error','Incorrect captcha');
}
```

Feel free to fork and contribute to it..
