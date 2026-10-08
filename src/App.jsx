import Benefits from './components/Benefits.jsx';
import FinalCta from './components/FinalCta.jsx';
import Footer from './components/Footer.jsx';
import Gallery from './components/Gallery.jsx';
import Header from './components/Header.jsx';
import Hero from './components/Hero.jsx';
import HowItWorks from './components/HowItWorks.jsx';
import ProductShowcase from './components/ProductShowcase.jsx';
import Trust from './components/Trust.jsx';

export default function App() {
  return (
    <>
      <a className="skip-link" href="#main">
        رفتن به محتوای اصلی
      </a>
      <Header />
      <main id="main" tabIndex={-1}>
        <Hero />
        <Benefits />
        <ProductShowcase />
        <HowItWorks />
        <Gallery />
        <Trust />
        <FinalCta />
      </main>
      <Footer />
    </>
  );
}
