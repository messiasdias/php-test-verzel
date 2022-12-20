import ImgAbout from './../../assets/images/about.png'

function About(){
    return (
        <section className="about" id="about">
            <div className="content">
                <div className="title-wrapper-about">
                <p>Saiba tudo sobre nós</p>
                <h3>Sobre</h3>
                </div>
                <div className="about-content">
                <div className="left">
                    <img src={ImgAbout} alt="About" />
                </div>
                <div className="right">
                    <h3>Junte tecnologia e mobilidade</h3>
                    <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Repudiandae ut inventore magni repellendus ab ad itaque facere. A
                    tenetur quam, nobis tempore illum laborum ipsa fuga, itaque
                    possimus repellat ad perspiciatis, velit reiciendis eos facilis
                    sapiente blanditiis quas officia hic beatae quibusdam! Quod
                    eligendi porro possimus voluptatum ad ipsum sapiente soluta,
                    maiores nobis tenetur adipisci officiis nisi illum quae natus
                    nostrum tempora accusantium blanditiis? Rem nesciunt illum
                    dolorum, perferendis quos consequatur suscipit cumque fugiat alias
                    sint repellat qui adipisci error est distinctio doloribus labore
                    sunt modi eos odio quibusdam dicta. Dignissimos voluptate illum
                    possimus quo. Ducimus repellat doloribus quisquam quidem?
                    </p>
                </div>
                </div>
            </div>
        </section>
    )
}

export default About