import React from 'react';
import { Dialog, RenderDialogProps } from './';
import { Button } from '@/components/elements/button/index';

type ConfirmationProps = Omit<RenderDialogProps, 'description' | 'children'> & {
    children: React.ReactNode;
    confirm?: string | undefined;
    variant?: 'danger' | 'primary' | undefined;
    onConfirmed: (e: React.MouseEvent<HTMLButtonElement, MouseEvent>) => void;
};

export default ({ confirm = 'Okay', children, variant = 'danger', onConfirmed, ...props }: ConfirmationProps) => {
    return (
        <Dialog {...props} description={typeof children === 'string' ? children : undefined}>
            {typeof children !== 'string' && children}
            <Dialog.Footer>
                <Button.Text onClick={props.onClose}>Cancel</Button.Text>
                {variant === 'primary' ? (
                    <Button onClick={onConfirmed}>{confirm}</Button>
                ) : (
                    <Button.Danger onClick={onConfirmed}>{confirm}</Button.Danger>
                )}
            </Dialog.Footer>
        </Dialog>
    );
};
