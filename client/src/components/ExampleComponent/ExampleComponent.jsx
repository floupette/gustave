// This component is an example on how to structure a component.

// All styles should come from a module.css file. Each component should have it's own style declarations
import styles from './ExampleComponent.module.css';

export default function ExampleComponent() {

    /**
     * A component content should follow this structure:
     * - First any router hooks (useNavigate, useLocation, ...)
     * - All states
     * - All use effects
     * - All event/callback functions
     */

    return (
        <p className={ styles['example-class'] }>This is an example</p>
    )
}