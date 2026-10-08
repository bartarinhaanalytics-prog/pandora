import { useRef } from 'react';
import { brand, hero } from '../data/content.js';
import HeroScene from './HeroScene.jsx';
import Icon from './Icon.jsx';
import './Hero.css';

export default function Hero() {
  const sectionRef = useRef(null);

  return (
    <section id="top" ref={sectionRef} className="hero on-night" aria-labelledby="hero-title">
      <div className="container hero__grid">
        <div className="hero__copy">
          <p className="hero__badge">
            <Icon name="Sparkles" size={16} />
            {hero.eyebrow}
          </p>
          <h1 id="hero-title" className="hero__title">
            {hero.title}
          </h1>
          <p className="hero__lead">{hero.lead}</p>
          <div className="hero__actions">
            <a href="#start" className="btn btn--accent">
              {brand.primaryCta}
              <Icon name="ArrowLeft" size={18} />
            </a>
            <a href="#product" className="btn btn--ghost">
              {hero.secondaryCta}
            </a>
          </div>
          <ul className="hero__proof" role="list">
            <li>
              <Icon name="ShieldCheck" size={18} />
              تأیید پزشک متخصص
            </li>
            <li>
              <Icon name="Lock" size={18} />
              بی‌نام و محرمانه
            </li>
          </ul>
        </div>
        <div className="hero__visual">
          <HeroScene alt={hero.sceneAlt} pointerTargetRef={sectionRef} />
        </div>
      </div>
    </section>
  );
}
