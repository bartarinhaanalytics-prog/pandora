import { ArrowLeft, Bird, BookHeart, Check, ChevronDown, CircleAlert, Fish, Loader2, Menu, Moon, Pause, Phone, Play, Rabbit, RotateCcw, ShieldCheck, Stethoscope, X } from 'lucide-react';

const map = { ArrowLeft, Bird, BookHeart, Check, ChevronDown, CircleAlert, Fish, Loader2, Menu, Moon, Pause, Phone, Play, Rabbit, RotateCcw, ShieldCheck, Stethoscope, X };

export default function Icon({ name, size = 20, ...rest }) {
  const C = map[name];
  return C ? <C size={size} strokeWidth={1.75} aria-hidden="true" focusable="false" {...rest} /> : null;
}
