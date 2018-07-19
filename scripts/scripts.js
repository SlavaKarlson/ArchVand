/**
 * Возвращает содержимое страницы
 *
 * @param {string} page имя страницы
 */
function LoadPage(page){
  $.ajax({
    url: '/archvand/handler.php?page='+page,
    success: function(msg){
      if(msg!='false'){
        $("#content").html($.parseJSON(msg));
      } else {
        $("#content").text('Error(php)');
      }
    }
  });
}

(function(){
  //Главная
  var menuMain=document.getElementById('menu-main');
  menuMain.onclick=function(){
    $(".menu__item_select").removeClass('menu__item_select');
    this.classList.add('menu__item_select');
    //----
    LoadPage('main');
  };
  //Разработки
  document.getElementById('menu-develop').onclick=function () {
    $(".menu__item_select").removeClass('menu__item_select');
    this.classList.add('menu__item_select');
    //----
    //$("#content").text('Разработки');
    LoadPage('develop');
  };
  //Медиа
  document.getElementById('menu-media').addEventListener('click',function () {
    $(".menu__item_select").removeClass('menu__item_select');
    this.classList.add('menu__item_select');
    //----
    //$("#content").text('Медиа');
    LoadPage('media');
  });
  //Контакты
  document.addEventListener('click',function (event) {
    if (event.target.id=='menu-contacts') {
      $(".menu__item_select").removeClass('menu__item_select');
      event.target.classList.add('menu__item_select');
      //----
      //$("#content").text('Контакты');
      LoadPage('contacts');
    }
    //Кнопка выход
    if (event.target.id=='auth-exit') {
      $.ajax({
        url:'/archvand/handler.php?act=exit_auth',
        success: function(msg){
          if (JSON.parse(msg)!=false){
            $('#auth').html(JSON.parse(msg));
          }
        }
      });
    }
  });
  //форма входа
  document.addEventListener('submit',function (event) {
    event.preventDefault();
    if (event.target.id=='auth-form') {
      var login=$('#auth-form input[name=login]').val();
      var pass=$('#auth-form input[name=pass]').val();

      $.post(
        "/archvand/handler.php",
        {
          act: "auth",
          login: login,
          pass: pass
        },
        function(msg){
          if (JSON.parse(msg)!=false){
            $('#auth').html(JSON.parse(msg));
          }
          else{
            alert('не верный логин/пароль');
          }
          return 0;
        }
      );
    }
  });

})();

//выполняется при полной загрузке страницы
$(function () {
  LoadPage('main');

  //Проверка авторизации
  $.ajax({
    url:"/archvand/handler.php?act=check_auth",
    success: function(msg){
      if(JSON.parse(msg)!=false){
        $('#auth').html(JSON.parse(msg));
      }
      return 0;
    }
  });
});
