import BackdropVideo from './BackdropVideo.jsx';
import StoryMaker from './StoryMaker.jsx';
import './Hero.css';

export default function Hero() {
  return (
    <section id="story" className="hero" aria-labelledby="hero-title">
      <BackdropVideo name="hero" portrait="heroPortrait" eager position="30% 50%" />
      <div className="hero__scrim" aria-hidden="true" />
      <div className="wrap hero__inner">
        <div className="hero__copy">
          <h1 id="hero-title">امشب، قصه را زیر کرسی بسازیم</h1>
          <p className="hero__lead">
            اسم بچه را بنویسید و قهرمانش را انتخاب کنید؛ قصه همین‌جا آماده می‌شود. دردونه همراه رایگان خانواده است، از آمادگی ازدواج تا
            قصهٔ شب.
          </p>
        </div>
        <StoryMaker />
      </div>
    </section>
  );
}
