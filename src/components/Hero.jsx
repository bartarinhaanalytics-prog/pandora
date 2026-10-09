import StoryMaker from './StoryMaker.jsx';
import './Hero.css';

export default function Hero() {
  return (
    <section id="story" className="hero" aria-labelledby="hero-title">
      <div className="wrap hero__inner">
        <div className="hero__copy">
          <h1 id="hero-title">امشب، زیر نور ماه قصه بسازیم</h1>
          <p className="hero__lead">با موضوعی که خودتان می‌خواهید، برای فرزندتان قصهٔ شب بسازید.</p>
        </div>
        <StoryMaker />
      </div>
    </section>
  );
}
