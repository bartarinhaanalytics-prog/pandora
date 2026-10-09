import { useEffect } from 'react';
import BottomNav from './components/BottomNav.jsx';
import CommunityPromo from './components/CommunityPromo.jsx';
import Footer from './components/Footer.jsx';
import Header from './components/Header.jsx';
import Health from './components/Health.jsx';
import Hero from './components/Hero.jsx';
import Lullabies from './components/Lullabies.jsx';
import PregnancyPromo from './components/PregnancyPromo.jsx';
import Signup from './components/Signup.jsx';
import StagePicker from './components/StagePicker.jsx';
import ToolsPromo from './components/ToolsPromo.jsx';
import Trust from './components/Trust.jsx';
import { MotionProvider } from './hooks/useMotion.jsx';
import { useRoute } from './lib/router.js';
import About from './pages/About.jsx';
import Ask from './pages/Ask.jsx';
import Community from './pages/Community.jsx';
import Contact from './pages/Contact.jsx';
import Cycle from './pages/Cycle.jsx';
import { HealthCategory, HealthIndex } from './pages/Health.jsx';
import LullabyLibrary from './pages/LullabyLibrary.jsx';
import Men from './pages/Men.jsx';
import NotFound from './pages/NotFound.jsx';
import Partner from './pages/Partner.jsx';
import Pregnancy from './pages/Pregnancy.jsx';
import Privacy from './pages/Privacy.jsx';
import Profile from './pages/Profile.jsx';
import QA from './pages/QA.jsx';
import Search from './pages/Search.jsx';
import Story from './pages/Story.jsx';
import Terms from './pages/Terms.jsx';
import { categories } from './data/articles.js';
import NightSky from './sky/NightSky.jsx';
import './components/Home.css';

const TITLES = {
  '/health': 'دسته‌های سلامت',
  '/pregnancy': 'بارداری هفته‌به‌هفته',
  '/search': 'جستجو',
  '/men': 'سلامت مردان و نقش پدر',
  '/qa': 'پرسش‌های سلامت',
  '/story': 'قصه‌ساز',
  '/lullabies': 'کتابخانهٔ لالایی',
  '/tools/cycle': 'تقویم قاعدگی و باروری',
  '/me': 'پروفایل و خانواده',
  '/me/partner': 'اتصال همسر',
  '/ask': 'پرسش بی‌نام از متخصص',
  '/community': 'جامعهٔ والدین',
  '/about': 'دربارهٔ ما',
  '/privacy': 'حریم خصوصی',
  '/terms': 'قوانین و سلب مسئولیت پزشکی',
  '/contact': 'تماس با ما'
};

function Home() {
  return (
    <main id="main" tabIndex={-1}>
      <span id="top" />
      <Hero />
      <StagePicker />
      <PregnancyPromo />
      <Health />
      <ToolsPromo />
      <Lullabies />
      <CommunityPromo />
      <Trust />
      <Signup />
    </main>
  );
}

function Page({ route }) {
  const { path, parts, query } = route;
  if (path === '/') return <Home />;
  switch (parts[0]) {
    case 'health':
      if (!parts[1]) return <HealthIndex />;
      if (parts[1] === 'men') return <Men query={query} />;
      return categories.some((c) => c.id === parts[1]) ? <HealthCategory id={parts[1]} query={query} /> : <NotFound />;
    case 'pregnancy':
      return <Pregnancy weekParam={parts[1]} />;
    case 'search':
      return <Search key={query.get('q') || ''} query={query} />;
    case 'men':
      return <Men query={query} />;
    case 'qa':
      return <QA query={query} />;
    case 'story':
      return <Story />;
    case 'lullabies':
      return <LullabyLibrary query={query} />;
    case 'tools':
      return parts[1] === 'cycle' ? <Cycle /> : <NotFound />;
    case 'me':
      return parts[1] === 'partner' ? <Partner /> : <Profile />;
    case 'ask':
      return <Ask />;
    case 'community':
      return <Community groupId={parts[1]} />;
    case 'about':
      return <About />;
    case 'privacy':
      return <Privacy />;
    case 'terms':
      return <Terms />;
    case 'contact':
      return <Contact />;
    default:
      return <NotFound />;
  }
}

export default function App() {
  const route = useRoute();

  useEffect(() => {
    const base = 'دردونه';
    const cat = route.parts[0] === 'health' && categories.find((c) => c.id === route.parts[1]);
    const t = cat ? cat.label : TITLES[`/${route.parts.slice(0, 2).join('/')}`] || TITLES[`/${route.parts[0] || ''}`];
    document.title = t ? `${t} | ${base}` : `${base} | قصهٔ شب، لالایی و سلامت خانواده، رایگان`;
  }, [route.path]);

  /* لنگرهای داخل صفحهٔ اول (#health) بعد از رسم صفحه */
  useEffect(() => {
    if (route.anchor) document.getElementById(route.anchor)?.scrollIntoView();
  }, [route.anchor]);

  return (
    <MotionProvider>
      <a href="#main" className="skip-link">
        رفتن به محتوای اصلی
      </a>
      <NightSky />
      <Header path={route.path} />
      <Page key={route.path} route={route} />
      <Footer />
      <BottomNav path={route.path} />
    </MotionProvider>
  );
}
