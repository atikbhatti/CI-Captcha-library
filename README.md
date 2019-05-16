# CI-Captcha-library
Simple MathCaptcha library for Codegniter

# Instructions

- copy Captcha.php to Codegniter Library folder

- Place this code where you want to print captcha

    $this->captcha->output();
    
- Varify Captcha

    $captcha 	= $this->input->post('captcha');
    
    if ($this->captcha->verify($captcha) == false)
				$this->session->set_flashdata('error','Incorrect captcha');
