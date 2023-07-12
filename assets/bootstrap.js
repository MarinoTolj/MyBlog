import { startStimulusApp } from '@symfony/stimulus-bridge';

const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));


export {app};