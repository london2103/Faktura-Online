$(document).ready(function(){
                $('#browsers img[title]').tooltip({
                    effect: "fade",
                    opacity: 0.7
                });
                $("#message").hide();
                $("#login").click(function(){
                        $.post("src/ajax/checkLogin.php",{username:$("#username").val(), password:$('#password').val(), logged:$('#logged').val()},function(data)
                        {
                           
                            if(data > 0)
                            {
                                $('#message').show().fadeTo(200, 0.1,function()
                                 {
                                   $(this).html("Login war erfolgreich").fadeTo(900,1,
                                   function()
                                   {
                                   window.location.href='index.php';
                               });
                            });
                            }
                            else{
                                
                                $('#message').show().fadeTo(200,0.1,function(){
                                    $(this).html("Login war nicht erfolgreich<br>Passwort oder Benutzername Falsch").fadeTo(900,1);
                                });
                            }
                        });
                        return false;
                         }); 
                       $('#username').focus();
                       
                    });

