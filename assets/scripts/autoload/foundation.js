import { Foundation } from 'foundation-sites/js/foundation.core';
import * as CoreUtils from 'foundation-sites/js/foundation.core.utils';
import { Box } from 'foundation-sites/js/foundation.util.box';
import { onImagesLoaded } from 'foundation-sites/js/foundation.util.imageLoader';
import { Keyboard } from 'foundation-sites/js/foundation.util.keyboard';
import { MediaQuery } from 'foundation-sites/js/foundation.util.mediaQuery';
import { Motion, Move } from 'foundation-sites/js/foundation.util.motion';
import { Nest } from 'foundation-sites/js/foundation.util.nest';
import { Timer } from 'foundation-sites/js/foundation.util.timer';
import { Touch } from 'foundation-sites/js/foundation.util.touch';
import { Triggers } from 'foundation-sites/js/foundation.util.triggers';
// import { Abide } from 'foundation-sites/js/foundation.abide';
// import { Accordion } from 'foundation-sites/js/foundation.accordion';
import { AccordionMenu } from 'foundation-sites/js/foundation.accordionMenu';
// import { Drilldown } from 'foundation-sites/js/foundation.drilldown';
// import { Dropdown } from 'foundation-sites/js/foundation.dropdown';
// import { DropdownMenu } from 'foundation-sites/js/foundation.dropdownMenu';
// import { Equalizer } from 'foundation-sites/js/foundation.equalizer';
// import { Interchange } from 'foundation-sites/js/foundation.interchange';
// import { Magellan } from 'foundation-sites/js/foundation.magellan';
// import { OffCanvas } from 'foundation-sites/js/foundation.offcanvas';
// import { Orbit } from 'foundation-sites/js/foundation.orbit';
import { ResponsiveMenu } from 'foundation-sites/js/foundation.responsiveMenu';
import { ResponsiveToggle } from 'foundation-sites/js/foundation.responsiveToggle';
// import { Reveal } from 'foundation-sites/js/foundation.reveal';
// import { Slider } from 'foundation-sites/js/foundation.slider';
// import { SmoothScroll } from 'foundation-sites/js/foundation.smoothScroll';
// import { Sticky } from 'foundation-sites/js/foundation.sticky';
// import { Tabs } from 'foundation-sites/js/foundation.tabs';
// import { Toggler } from 'foundation-sites/js/foundation.toggler';
// import { Tooltip } from 'foundation-sites/js/foundation.tooltip';
// import { ResponsiveAccordionTabs } from 'foundation-sites/js/foundation.responsiveAccordionTabs';

/* DO NOT EDIT THE CODE BELOW THIS LINE */
Foundation.addToJquery($);

// Add Foundation Utils to Foundation global namespace for backwards
// compatibility.
Foundation.rtl = CoreUtils.rtl;
Foundation.GetYoDigits = CoreUtils.GetYoDigits;
Foundation.transitionend = CoreUtils.transitionend;
Foundation.RegExpEscape = CoreUtils.RegExpEscape;
Foundation.onLoad = CoreUtils.onLoad;

Foundation.Box = Box;
Foundation.onImagesLoaded = onImagesLoaded;
Foundation.Keyboard = Keyboard;
Foundation.MediaQuery = MediaQuery;
Foundation.Motion = Motion;
Foundation.Move = Move;
Foundation.Nest = Nest;
Foundation.Timer = Timer;

// Touch and Triggers previously were almost purely sede effect driven,
// so no need to add it to Foundation, just init them.
Touch.init($);
Triggers.init($, Foundation);
MediaQuery._init();

/* eslint-disable no-undef */
if (typeof Abide !== 'undefined') {
  Foundation.plugin(Abide, 'Abide');
}
if (typeof Accordion !== 'undefined') {
  Foundation.plugin(Accordion, 'Accordion');
}
if (typeof AccordionMenu !== 'undefined') {
  Foundation.plugin(AccordionMenu, 'AccordionMenu');
}
if (typeof Drilldown !== 'undefined') {
  Foundation.plugin(Drilldown, 'Drilldown');
}
if (typeof Dropdown !== 'undefined') {
  Foundation.plugin(Dropdown, 'Dropdown');
}
if (typeof DropdownMenu !== 'undefined') {
  Foundation.plugin(DropdownMenu, 'DropdownMenu');
}
if (typeof Equalizer !== 'undefined') {
  Foundation.plugin(Equalizer, 'Equalizer');
}
if (typeof Interchange !== 'undefined') {
  Foundation.plugin(Interchange, 'Interchange');
}
if (typeof Magellan !== 'undefined') {
  Foundation.plugin(Magellan, 'Magellan');
}
if (typeof OffCanvas !== 'undefined') {
  Foundation.plugin(OffCanvas, 'OffCanvas');
}
if (typeof Orbit !== 'undefined') {
  Foundation.plugin(Orbit, 'Orbit');
}
if (typeof ResponsiveMenu !== 'undefined') {
  Foundation.plugin(ResponsiveMenu, 'ResponsiveMenu');
}
if (typeof ResponsiveToggle !== 'undefined') {
  Foundation.plugin(ResponsiveToggle, 'ResponsiveToggle');
}
if (typeof Reveal !== 'undefined') {
  Foundation.plugin(Reveal, 'Reveal');
}
if (typeof Slider !== 'undefined') {
  Foundation.plugin(Slider, 'Slider');
}
if (typeof SmoothScroll !== 'undefined') {
  Foundation.plugin(SmoothScroll, 'SmoothScroll');
}
if (typeof Sticky !== 'undefined') {
  Foundation.plugin(Sticky, 'Sticky');
}
if (typeof Tabs !== 'undefined') {
  Foundation.plugin(Tabs, 'Tabs');
}
if (typeof Toggler !== 'undefined') {
  Foundation.plugin(Toggler, 'Toggler');
}
if (typeof Tooltip !== 'undefined') {
  Foundation.plugin(Tooltip, 'Tooltip');
}
if (typeof ResponsiveAccordionTabs !== 'undefined') {
  Foundation.plugin(ResponsiveAccordionTabs, 'ResponsiveAccordionTabs');
}
/* eslint-enable no-undef */

export default Foundation;
