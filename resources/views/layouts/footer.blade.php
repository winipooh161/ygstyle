<footer>
    <div class="container">
        <div class="footer__body">
            <div class="footer__body__logo">
                <a href="{{url('/')}}"><img src="{{asset('img/icon/logo.svg')}}" alt=""></a>
            </div>
            <div class="footer__body__uls">
                <ul>
                    <p>Наши соцсети:</p>
                    <div class="icons">
                        <a class="icon-info" href="">
                            <img src="{{asset('img/icon/tg.svg')}}" alt="">
                        </a>
                        <a class="icon-info" href="">
                            <img src="{{asset('img/icon/wa.svg')}}" alt="">
                        </a>
                        <a class="icon-info" href="">
                            <img src="{{asset('img/icon/ru.svg')}}" alt="">
                        </a>
                        <a class="icon-info" href="">
                            <img src="{{asset('img/icon/dz.svg')}}" alt="">
                        </a>
                        <a class="icon-info" href="">
                            <img src="{{asset('img/icon/vk.svg')}}" alt="">
                        </a>
                    </div>
                </ul>
                <ul>
                    <p> Навигация:</p>
                    <li><a href="{{url('/projects')}}">Выполненные проекты </a></li>
                    <li><a href="#tabs">Этапы работы</a></li>
                    <li><a href="#info">Гарантии</a></li>
                    <li><a href="#services">комплекс
                        услуг </a></li>
                    <li><a href="#avtor">Дизайн-проект</a></li>
                    <li><a href="{{ url('/gallery') }}">Галерея</a></li>
                    <li><a href="#map">О компании</a></li>
                </ul>
                
                <ul>
                    <p>Информация::</p>
                    <li><a href="#">Реквизиты компании</a></li>
                    <li><a href="#">Свидетельство регистрации ип</a></li>
                    <li><a href="#">Договор оферты</a></li>
                    <li><a href="#">Политика конфиденциальности</a></li>
                </ul>
                <ul>
                    <li><a href="tel:+7 (961) 276 61-62">+7 (961) 276 61-62</a></li>
                    <li><a href="mailto:info@yugstil.ru">info@yugstil.ru</a></li>
                    <span>ООО «ЮГ СТРОЙ» <br> ИНН 6908020210 <br> ОГРН 1226900008158</span>
                </ul>
            </div>
        </div>
    </div>
   
   <div class="social-icons">
        <button class="menu-toggle"><svg width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="2" cy="2" r="2" fill="white" />
                <circle cx="10" cy="2" r="2" fill="white" />
                <circle cx="18" cy="2" r="2" fill="white" />
            </svg>
        </button>
        <button class="close-menu" style="display: none;"><svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.57695 1L13 12.423M12.423 1L1 12.423" stroke="white" stroke-width="1.1033" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    <a class="icon" href="">
                            <img src="{{asset('img/icon/tg.svg')}}" alt="">
                        </a>
                        <a class="icon" href="">
                            <img src="{{asset('img/icon/wa.svg')}}" alt="">
                        </a>
                        <a class="icon" href="">
                            <img src="{{asset('img/icon/ru.svg')}}" alt="">
                        </a>
                        <a class="icon" href="">
                            <img src="{{asset('img/icon/dz.svg')}}" alt="">
                        </a>
                        <a class="icon" href="">
                            <img src="{{asset('img/icon/vk.svg')}}" alt="">
                        </a>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const socialIcons = document.querySelector('.social-icons');
            const menuToggle = document.querySelector('.menu-toggle');
            const closeMenu = document.querySelector('.close-menu');

            menuToggle.addEventListener('click', function() {
                socialIcons.classList.add('open');
                menuToggle.style.display = 'none';
                closeMenu.style.display = 'flex';
            });

            closeMenu.addEventListener('click', function() {
                socialIcons.classList.remove('open');
                closeMenu.style.display = 'none';
                menuToggle.style.display = 'flex';
            });
        });
    </script>
</footer>
