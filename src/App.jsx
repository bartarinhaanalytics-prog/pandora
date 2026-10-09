import Footer from './components/Footer.jsx';
import Header from './components/Header.jsx';
import Health from './components/Health.jsx';
import Hero from './components/Hero.jsx';
import Lullabies from './components/Lullabies.jsx';
import Signup from './components/Signup.jsx';
import Trust from './components/Trust.jsx';
import { MotionProvider } from './hooks/useMotion.jsx';

export default function App() {
  return (
    <MotionProvider>
      <a href="#main" className="skip-link">
        رفتن به محتوای اصلی
      </a>
      <Header />
      <main id="main" tabIndex={-1}>
        <span id="top" />
        <Hero />
        <Health />
        <Lullabies />
        <Trust />
        <Signup />
      </main>
      <Footer />
    </MotionProvider>
  );
}
